<?php
// controllers/LedgerController.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

/**
 * Handles rendering the main accounting general ledger dashboard summaries
 */
function handleLedgerDashboard($pdo)
{
    checkAuthenticated($pdo);

    $search = trim($_GET['search'] ?? '');

    try {
        // --- NEW KPI DATA ---
        $stmtKpi = $pdo->query("SELECT COUNT(*) FROM journal_vouchers WHERE status = 'pending'");
        $pending_count = $stmtKpi->fetchColumn();

        $stmtEquity = $pdo->query("SELECT SUM(credit - debit) FROM ledger_entries le 
                                   JOIN journal_vouchers jv ON le.voucher_id = jv.id 
                                   WHERE jv.status = 'approved'");
        $total_coop_equity = $stmtEquity->fetchColumn() ?: 0;
        // --------------------

        // Base Query: We group by everything we select to satisfy SQL requirements
        $sqlSummary = "SELECT 
                        m.id AS member_id, 
                        m.member_number, 
                        m.first_name, 
                        m.last_name,
                        COALESCE(SUM(CASE WHEN le.entry_type IN ('deposit', 'dividend') THEN le.credit ELSE 0 END), 0) AS total_credits,
                        COALESCE(SUM(CASE WHEN le.entry_type IN ('withdrawal', 'mrs_deduction') THEN le.debit ELSE 0 END), 0) AS total_debits,
                        (COALESCE(SUM(CASE WHEN le.entry_type IN ('deposit', 'dividend') THEN le.credit ELSE 0 END), 0) - 
                         COALESCE(SUM(CASE WHEN le.entry_type IN ('withdrawal', 'mrs_deduction') THEN le.debit ELSE 0 END), 0)) AS current_balance
                       FROM members m
                       LEFT JOIN ledger_entries le ON m.id = le.member_id
                       LEFT JOIN journal_vouchers jv ON le.voucher_id = jv.id
                       WHERE (jv.status = 'approved' OR jv.status IS NULL)";

        $params = [];

        // Add search filtering if provided
        if (!empty($search)) {
            $sqlSummary .= " AND (m.first_name LIKE ? OR m.last_name LIKE ? OR m.member_number LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%"];
        }

        // Add the single Group By and Order By
        $sqlSummary .= " GROUP BY m.id, m.member_number, m.first_name, m.last_name ORDER BY m.id ASC";

        $stmtSummary = $pdo->prepare($sqlSummary);
        $stmtSummary->execute($params);
        $member_summaries = $stmtSummary->fetchAll();

        // Fetch recent transaction activity
        $start_date = trim($_GET['start_date'] ?? '');
        $end_date   = trim($_GET['end_date'] ?? '');

        $sqlVouchers = "SELECT jv.*, u.username AS operator_name 
                        FROM journal_vouchers jv
                        LEFT JOIN users u ON jv.created_by = u.id
                        WHERE 1=1";

        $voucherParams = [];
        if (!empty($start_date)) {
            $sqlVouchers .= " AND DATE(jv.transaction_date) >= ?";
            $voucherParams[] = $start_date;
        }
        if (!empty($end_date)) {
            $sqlVouchers .= " AND DATE(jv.transaction_date) <= ?";
            $voucherParams[] = $end_date;
        }

        $sqlVouchers .= " ORDER BY jv.transaction_date DESC, jv.id DESC LIMIT 50";

        $stmtVouchers = $pdo->prepare($sqlVouchers);
        $stmtVouchers->execute($voucherParams);
        $vouchers = $stmtVouchers->fetchAll();

        include dirname(__DIR__) . '/views/ledger_dashboard.php';
    } catch (PDOException $e) {
        die("Database error loading ledger dashboard: " . $e->getMessage());
    }
}

function handleCreateLedgerEntry($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $member_id        = intval($_POST['member_id'] ?? 0);
        $entry_type       = trim($_POST['entry_type'] ?? '');
        $amount           = floatval($_POST['amount'] ?? 0.00);
        $particulars      = trim($_POST['particulars'] ?? 'Manual ledger entry');
        $reference_number = trim($_POST['reference_number'] ?? '');

        if (!empty($reference_number)) {
            $stmtCheck = $pdo->prepare("SELECT id FROM journal_vouchers WHERE reference_number = ?");
            $stmtCheck->execute([$reference_number]);
            if ($stmtCheck->fetch()) {
                $_SESSION['error_message'] = "Error: Reference Number '$reference_number' is already in use.";
                header("Location: index.php?route=add_ledger_entry");
                exit;
            }
        } else {
            $reference_number = "JV-" . strtoupper(uniqid());
        }

        try {
            $pdo->beginTransaction();

            $sqlVoucher = "INSERT INTO journal_vouchers (reference_number, transaction_date, particulars, created_by, status) 
                           VALUES (?, NOW(), ?, ?, 'pending')";
            $stmtV = $pdo->prepare($sqlVoucher);
            $stmtV->execute([$reference_number, $particulars, $_SESSION['user_id']]);
            $voucher_id = $pdo->lastInsertId();

            $debit = (in_array($entry_type, ['deposit', 'dividend'])) ? 0.00 : $amount;
            $credit = (in_array($entry_type, ['deposit', 'dividend'])) ? $amount : 0.00;

            $sqlEntry = "INSERT INTO ledger_entries (voucher_id, member_id, entry_type, debit, credit) 
                         VALUES (?, ?, ?, ?, ?)";
            $stmtE = $pdo->prepare($sqlEntry);
            $stmtE->execute([$voucher_id, $member_id, $entry_type, $debit, $credit]);

            $pdo->commit();
            $_SESSION['success_message'] = "Voucher $reference_number submitted for approval.";
            header("Location: index.php?route=ledger");
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            die("Database transaction aborted: " . $e->getMessage());
        }
    }

    try {
        $stmtMembers = $pdo->query("SELECT id, member_number, first_name, last_name FROM members ORDER BY last_name ASC");
        $members = $stmtMembers->fetchAll();
        include dirname(__DIR__) . '/views/ledger_entry_add.php';
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

function handleLedgerStatement($pdo)
{
    checkAuthenticated($pdo);
    $member_id = intval($_GET['id'] ?? 0);
    $start_date = trim($_GET['start_date'] ?? '');
    $end_date = trim($_GET['end_date'] ?? '');

    try {
        $stmtMember = $pdo->prepare("SELECT * FROM members WHERE id = ? LIMIT 1");
        $stmtMember->execute([$member_id]);
        $member = $stmtMember->fetch();

        if (!$member) die("Error: Target member profile does not exist.");

        // --- NEW DATE FILTER LOGIC ---
        $sqlTransactions = "SELECT le.entry_type, le.debit, le.credit, jv.transaction_date, jv.reference_number, jv.particulars
                            FROM ledger_entries le
                            JOIN journal_vouchers jv ON le.voucher_id = jv.id
                            WHERE le.member_id = ? AND jv.status = 'approved'";

        $params = [$member_id];

        if (!empty($start_date)) {
            $sqlTransactions .= " AND DATE(jv.transaction_date) >= ?";
            $params[] = $start_date;
        }
        if (!empty($end_date)) {
            $sqlTransactions .= " AND DATE(jv.transaction_date) <= ?";
            $params[] = $end_date;
        }

        $sqlTransactions .= " ORDER BY jv.transaction_date ASC, le.id ASC";

        $stmtTx = $pdo->prepare($sqlTransactions);
        $stmtTx->execute($params);
        $history = $stmtTx->fetchAll();

        include dirname(__DIR__) . '/views/ledger_statement.php';
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

function handlePendingApprovals($pdo)
{
    checkAuthenticated($pdo);

    $staff_role_id = 2;

    if (
        !isset($_SESSION['role_id']) ||
        (intval($_SESSION['role_id']) !== 1 && intval($_SESSION['role_id']) !== $staff_role_id)
    ) {

        $_SESSION['error_message'] = "Access Denied: You do not have permission to view pending approvals.";
        header("Location: index.php?route=ledger");
        exit;
    }

    $sql = "SELECT jv.*, le.entry_type, le.credit, le.debit, m.first_name, m.last_name 
            FROM journal_vouchers jv
            JOIN ledger_entries le ON jv.id = le.voucher_id
            JOIN members m ON le.member_id = m.id
            WHERE jv.status = 'pending'
            ORDER BY jv.transaction_date ASC";

    $stmt = $pdo->query($sql);
    $pending_vouchers = $stmt->fetchAll();
    include dirname(__DIR__) . '/views/ledger_pending.php';
}

function handleApproveVoucher($pdo)
{
    checkAuthenticated($pdo);

    if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
        die("Access Denied: You do not have permission to perform this action.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $voucher_id = intval($_POST['voucher_id'] ?? 0);
        try {
            $stmt = $pdo->prepare("UPDATE journal_vouchers SET status = 'approved' WHERE id = ?");
            $stmt->execute([$voucher_id]);

            logSystemActivity($pdo, 'VOUCHER_APPROVAL', "Approved journal voucher ID #{$voucher_id}");

            $_SESSION['success_message'] = "Voucher approved successfully.";
            header("Location: index.php?route=pending_approvals");
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
