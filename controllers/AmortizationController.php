<?php
// controllers/AmortizationController.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

require_once dirname(__DIR__) . '/helpers/AmortizationEngine.php';

/**
 * Background Automation Utility: Scans and updates overdue schedule rows and penalties
 */
function checkAndUpdateOverdueSchedules($pdo)
{
    try {
        // 1. Fetch all unpaid schedule periods for active (Approved) loans
        $sql = "SELECT ls.*, l.interest_rate, l.principal 
                FROM loan_schedules ls
                JOIN loans l ON ls.loan_id = l.id
                WHERE ls.status != 'paid' AND l.loan_status = 'Approved'";

        $stmt = $pdo->query($sql);
        $schedules = $stmt->fetchAll();

        $todayStr = date('Y-m-d');

        $stmtUpdate = $pdo->prepare("UPDATE loan_schedules 
                                     SET status = ?, rem_penalty = ? 
                                     WHERE id = ?");

        foreach ($schedules as $row) {
            $status = $row['status'];
            $penalty = floatval($row['rem_penalty']);
            $isUpdated = false;

            // Check if the payment target timeline has passed today's date
            if ($row['due_date'] < $todayStr) {
                // Change state to overdue if it was still marked pending
                if ($status === 'pending') {
                    $status = 'overdue';
                    $isUpdated = true;
                }

                // Calculate how many months late this period is using the engine helper
                $monthsOverdue = AmortizationEngine::calculateMonthsOverdue($row['due_date']);

                if ($monthsOverdue > 0) {
                    // Rule Example: Apply a standard 5% late penalty fee on the remaining principal per month late
                    $monthlyPenaltyRate = 0.05;
                    $calculatedPenalty = floatval($row['rem_principal']) * $monthlyPenaltyRate * $monthsOverdue;

                    // Update if the penalty changes
                    if ($calculatedPenalty != $penalty) {
                        $penalty = $calculatedPenalty;
                        $isUpdated = true;
                    }
                }
            }

            // Only run a database update query if changes actually occurred
            if ($isUpdated) {
                $stmtUpdate->execute([$status, $penalty, $row['id']]);
            }
        }
    } catch (PDOException $e) {
        // Log error or handle gracefully so it doesn't disrupt the main dashboard load
        error_log("Overdue schedule automation error: " . $e->getMessage());
    }
}

/**
 * Renders the main Amortization Dashboard listing only approved, active loan allocations
 */
function handleAmortizationDashboard($pdo)
{
    checkAuthenticated($pdo);

    // AUTOMATION TRIGGER: Run the background validation scanner immediately upon dashboard rendering
    checkAndUpdateOverdueSchedules($pdo);

    // Filters dashboard records so only verified, fully active loans appear on the screen
    $sql = "SELECT l.*, m.first_name, m.last_name, m.member_number 
            FROM loans l 
            JOIN members m ON l.member_id = m.id 
            WHERE l.loan_status = 'Approved' 
            ORDER BY l.id DESC";

    $loans = $pdo->query($sql)->fetchAll();
    include dirname(__DIR__) . '/views/amortization_dashboard.php';
}

/**
 * Handles creation of a loan request; automatically saves under 'Pending' status
 */
function handleCreateLoan($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $member_id            = intval($_POST['member_id'] ?? 0);
        $loan_type            = trim($_POST['loan_type'] ?? '');
        $collateral           = trim($_POST['collateral'] ?? '');
        $amortization_type    = trim($_POST['amortization_type'] ?? '');
        $payment_frequency    = trim($_POST['payment_frequency'] ?? 'Monthly');
        $principal            = floatval($_POST['principal'] ?? 0.00);
        $interest_rate        = floatval($_POST['interest_rate'] ?? 0.00);
        $terms                = intval($_POST['terms'] ?? 0);
        $start_date           = trim($_POST['start_date'] ?? '');
        $tct_no               = trim($_POST['tct_no'] ?? '');
        $tax_declaration_no   = trim($_POST['tax_declaration_no'] ?? '');
        $real_property_status = trim($_POST['real_property_status'] ?? '');

        $undertaking_doc      = trim($_POST['undertaking_doc'] ?? '');
        $deed_of_rights_doc   = trim($_POST['deed_of_rights_doc'] ?? '');

        try {
            // Force status to 'Pending' upon creation to feed the Admin validation queue
            $sql = "INSERT INTO loans (member_id, loan_type, collateral, soa_status, loan_status, 
                                      amortization_type, payment_frequency, principal, interest_rate, 
                                      terms, start_date, tct_no, tax_declaration_no, real_property_status, 
                                      undertaking_doc, deed_of_rights_doc) 
                    VALUES (?, ?, ?, 'Pending', 'Pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $member_id,
                $loan_type,
                $collateral,
                $amortization_type,
                $payment_frequency,
                $principal,
                $interest_rate,
                $terms,
                $start_date,
                $tct_no,
                $tax_declaration_no,
                $real_property_status,
                $undertaking_doc,
                $deed_of_rights_doc
            ]);

            $_SESSION['success_message'] = "Loan request encoded successfully and sent to Admin Approval Queue.";
            header("Location: index.php?route=amortization_dashboard");
            exit;
        } catch (PDOException $e) {
            die("Database transaction aborted during loan creation: " . $e->getMessage());
        }
    }

    $members = $pdo->query("SELECT id, first_name, last_name, member_number FROM members ORDER BY last_name ASC")->fetchAll();
    include dirname(__DIR__) . '/views/loan_create.php';
}

/**
 * View profiles, timeline logs, and statements of specific loans
 */
function handleViewLoan($pdo)
{
    checkAuthenticated($pdo);
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        header("Location: index.php?route=amortization_dashboard");
        exit;
    }

    $stmt = $pdo->prepare("SELECT l.*, m.first_name, m.last_name, m.member_number FROM loans l JOIN members m ON l.member_id = m.id WHERE l.id = ?");
    $stmt->execute([$id]);
    $loanData = $stmt->fetch();

    if (!$loanData) {
        die("Requested account traces are missing.");
    }

    $stmtRows = $pdo->prepare("SELECT * FROM loan_schedules WHERE loan_id = ? ORDER BY period ASC");
    $stmtRows->execute([$id]);
    $rows = $stmtRows->fetchAll();

    $stmtLedger = $pdo->prepare("SELECT * FROM payment_ledger WHERE loan_id = ? ORDER BY id DESC");
    $stmtLedger->execute([$id]);
    $ledger = $stmtLedger->fetchAll();

    include dirname(__DIR__) . '/views/loan_view.php';
}

/**
 * Handles applying incoming processing waterfall transactions
 */
function handleApplyPayment($pdo)
{
    checkAuthenticated($pdo);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $loan_id = intval($_POST['loan_id'] ?? 0);

        // Defensive Verification: Restrain payment collection attempts on unapproved assets
        $stmt = $pdo->prepare("SELECT loan_status FROM loans WHERE id = ?");
        $stmt->execute([$loan_id]);
        $status = $stmt->fetchColumn();

        if ($status !== 'Approved') {
            die("Transaction Restrained: Cannot post payments to an unapproved or inactive account line.");
        }

        // ... (Your existing payment waterfall math code engine continues execution here) ...
        header("Location: index.php?route=view_loan&id=" . $loan_id);
        exit;
    }
}

/**
 * Generates printer safe view structures
 */
function handlePrintSOA($pdo)
{
    checkAuthenticated($pdo);
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        header("Location: index.php?route=amortization_dashboard");
        exit;
    }

    $stmt = $pdo->prepare("SELECT l.*, m.first_name, m.last_name, m.member_number 
                           FROM loans l 
                           JOIN members m ON l.member_id = m.id 
                           WHERE l.id = ?");
    $stmt->execute([$id]);
    $loanData = $stmt->fetch();

    if (!$loanData) {
        die("Requested account traces are missing.");
    }

    $stmtRows = $pdo->prepare("SELECT * FROM loan_schedules WHERE loan_id = ? ORDER BY period ASC");
    $stmtRows->execute([$id]);
    $rows = $stmtRows->fetchAll();

    $stmtLedger = $pdo->prepare("SELECT * FROM payment_ledger WHERE loan_id = ? ORDER BY id ASC");
    $stmtLedger->execute([$id]);
    $ledger = $stmtLedger->fetchAll();

    include dirname(__DIR__) . '/views/loan_print.php';
}

/**
 * Handles emergency schedule updates
 */
function handleEditSchedulePeriod($pdo)
{
    checkAuthenticated($pdo);

    // Administrative Access Lock: Revisions to current payment arrays are admin-only
    if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
        die("Security Error: Unauthorized profile configuration modification attempt.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['schedule_id']);
        $loan_id = intval($_POST['loan_id']);
        $due_date = $_POST['due_date'];
        $penalty = floatval($_POST['penalty']);
        $remarks = trim($_POST['remarks']);

        $stmt = $pdo->prepare("UPDATE loan_schedules SET due_date = ?, rem_penalty = ?, remarks = ? WHERE id = ?");
        $stmt->execute([$due_date, $penalty, $remarks, $id]);

        header("Location: index.php?route=view_loan&id=" . $loan_id);
        exit;
    }
}

/**
 * Administrative View for inspecting unverified records
 */
function handlePendingLoansQueue($pdo)
{
    checkAuthenticated($pdo);

    // Enforce administrative authentication requirements
    if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
        $_SESSION['error_message'] = "Access Denied: Administrative security privileges required.";
        header("Location: index.php?route=amortization_dashboard");
        exit;
    }

    $sql = "SELECT l.*, m.first_name, m.last_name, m.member_number 
            FROM loans l 
            JOIN members m ON l.member_id = m.id 
            WHERE l.loan_status = 'Pending' 
            ORDER BY l.created_at ASC";

    $pending_loans = $pdo->query($sql)->fetchAll();

    include dirname(__DIR__) . '/views/loan_pending.php';
}

/**
 * Performs transaction commitment confirmation operations and automatically builds repayment matrices
 */
function handleProcessLoanApproval($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Enforce role-based structural validation
        if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
            die("Security Escalation Blocked: Unauthorized administrative operations request.");
        }

        $loan_id = intval($_POST['loan_id'] ?? 0);
        $action = $_POST['action'] ?? ''; // Expects 'Approve' or 'Reject'

        if ($loan_id <= 0 || !in_array($action, ['Approve', 'Reject'])) {
            die("Validation Error: Missing parameters.");
        }

        try {
            $pdo->beginTransaction();

            if ($action === 'Approve') {
                // 1. Activate the Loan status line item
                $stmt = $pdo->prepare("UPDATE loans SET loan_status = 'Approved' WHERE id = ?");
                $stmt->execute([$loan_id]);

                // 2. Fetch all configuration parameters to pass to the engine
                $stmtLoan = $pdo->prepare("SELECT * FROM loans WHERE id = ?");
                $stmtLoan->execute([$loan_id]);
                $loan = $stmtLoan->fetch();

                if (!$loan) {
                    throw new Exception("Target loan account data row missing.");
                }

                // 3. Invoke the engine helper to compute the payment table arrays
                $scheduleRows = AmortizationEngine::generateSchedule([
                    'principal'         => $loan['principal'],
                    'interest_rate'     => $loan['interest_rate'],
                    'terms'             => $loan['terms'],
                    'start_date'        => $loan['start_date'],
                    'amortization_type' => $loan['amortization_type'],
                    'loan_type'         => $loan['loan_type'],
                    'payment_frequency' => $loan['payment_frequency'] ?? 'Monthly',
                    'manual_payment'    => $loan['manual_payment'] ?? 0
                ]);

                $sqlInsertSchedule = "INSERT INTO loan_schedules (
                                        loan_id, period, due_date, principal, interest, 
                                        rem_principal, rem_interest, rem_penalty, status, remarks
                                      ) VALUES (?, ?, ?, ?, ?, ?, ?, 0.00, 'pending', '')";

                $stmtInsert = $pdo->prepare($sqlInsertSchedule);

                foreach ($scheduleRows as $row) {
                    $stmtInsert->execute([
                        $loan_id,
                        $row['period'],
                        $row['due_date'],
                        $row['principal'],
                        $row['interest'],
                        $row['principal'],
                        $row['interest']
                    ]);
                }

                $_SESSION['success_message'] = "Loan profile allocation approved, activated, and repayment matrix instantiated successfully.";
            } else {
                $stmt = $pdo->prepare("UPDATE loans SET loan_status = 'Rejected' WHERE id = ?");
                $stmt->execute([$loan_id]);
                $_SESSION['success_message'] = "Loan profile application marked as Rejected.";
            }

            $pdo->commit();
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            die("Transaction Failed: " . $e->getMessage());
        }
    }

    header("Location: index.php?route=pending_loans_queue");
    exit;
}
