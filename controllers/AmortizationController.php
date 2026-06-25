<?php
// controllers/AmortizationController.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

require_once __DIR__ . '/../helpers/AmortizationEngine.php';

/**
 * Background Automation Utility: Scans and updates overdue schedule rows and penalties
 */
function checkAndUpdateOverdueSchedules($pdo)
{
    try {
        $sql = "SELECT ls.*, l.interest_rate, l.principal 
                FROM loan_schedules ls
                JOIN loans l ON ls.loan_id = l.id
                WHERE ls.status != 'paid' AND l.loan_status = 'Approved'";

        $stmt = $pdo->query($sql);
        $schedules = $stmt->fetchAll();

        $todayStr = date('Y-m-d');
        $stmtUpdate = $pdo->prepare("UPDATE loan_schedules SET status = ?, rem_penalty = ? WHERE id = ?");

        foreach ($schedules as $row) {
            $status = $row['status'];
            $penalty = floatval($row['rem_penalty']);
            $isUpdated = false;

            if ($row['due_date'] < $todayStr) {
                if ($status === 'pending') {
                    $status = 'overdue';
                    $isUpdated = true;
                }

                $daysOverdue = (strtotime($todayStr) - strtotime($row['due_date'])) / (60 * 60 * 24);
                if ($daysOverdue > 0) {
                    $calculatedPenalty = floatval($row['rem_principal']) * (0.01 / 30) * $daysOverdue;
                    if ($calculatedPenalty > $penalty) {
                        $penalty = $calculatedPenalty;
                        $isUpdated = true;
                    }
                }
            }

            if ($isUpdated) {
                $stmtUpdate->execute([$status, $penalty, $row['id']]);
            }
        }
    } catch (Exception $e) {
        error_log("Automation Failure checking overdue amortizations: " . $e->getMessage());
    }
}

/**
 * Renders the main Amortization Dashboard listing only approved, active loan allocations
 */
function handleAmortizationDashboard($pdo)
{
    checkAuthenticated($pdo);
    checkAndUpdateOverdueSchedules($pdo);

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
        $amortization_type    = trim($_POST['amortization_type'] ?? 'Diminishing Balance');
        $principal            = floatval($_POST['principal'] ?? 0);
        $interest_rate        = floatval($_POST['interest_rate'] ?? 0);
        $terms                = intval($_POST['terms'] ?? 0);
        $payment_frequency    = trim($_POST['payment_frequency'] ?? 'Monthly');
        $start_date           = $_POST['start_date'] ?? date('Y-m-d');
        $manual_payment       = floatval($_POST['manual_payment'] ?? 0.00);

        if ($member_id <= 0 || empty($loan_type) || $principal <= 0 || $interest_rate < 0 || $terms <= 0) {
            die("Validation Error: Please configure all required structural parameters correctly.");
        }

        try {
            $sql = "INSERT INTO loans (
                        member_id, loan_type, collateral, amortization_type, 
                        payment_frequency, principal, interest_rate, terms, 
                        start_date, manual_payment, loan_status
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

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
                $manual_payment
            ]);

            $new_loan_id = $pdo->lastInsertId();
            logSystemActivity($pdo, 'LOAN_CREATED', "Loan application created for Member ID {$member_id}, Amount: {$principal}");

            $_SESSION['success_message'] = "Loan profile application successfully staged into the verification queue.";
            if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
                header("Location: index.php?route=pending_loans_queue");
            } else {
                header("Location: index.php?route=amortization_dashboard");
            }
            exit;
        } catch (PDOException $e) {
            die("Database Error submitting credit request: " . $e->getMessage());
        }
    }

    $members = $pdo->query("SELECT id, member_number, first_name, last_name FROM members ORDER BY last_name ASC")->fetchAll();
    include dirname(__DIR__) . '/views/loan_create.php';
}

/**
 * Displays detailed payment scheduling matrix trace metrics
 */
function handleViewLoan($pdo)
{
    checkAuthenticated($pdo);

    $loan_id = intval($_GET['id'] ?? 0);
    if ($loan_id <= 0) {
        die("Error: Context loan identifier mapping omitted.");
    }

    try {
        $sqlLoan = "SELECT l.*, m.first_name, m.last_name, m.member_number 
                    FROM loans l
                    JOIN members m ON l.member_id = m.id
                    WHERE l.id = ?";
        $stmtLoan = $pdo->prepare($sqlLoan);
        $stmtLoan->execute([$loan_id]);
        $loan = $stmtLoan->fetch();

        if (!$loan) {
            die("Error: Loan profile entry traces trace missing inside the database.");
        }

        $sqlSchedule = "SELECT * FROM loan_schedules WHERE loan_id = ? ORDER BY period ASC";
        $stmtSchedule = $pdo->prepare($sqlSchedule);
        $stmtSchedule->execute([$loan_id]);
        $schedule = $stmtSchedule->fetchAll();

        // Safe check for ledger table existence/records based on HTML structure
        $ledger = $pdo->query("SELECT *, datetime FROM payment_ledger WHERE loan_id = {$loan_id} ORDER BY id DESC")->fetchAll();

        $loanData = $loan;
        $rows     = $schedule;

        include dirname(__DIR__) . '/views/loan_view.php';
    } catch (PDOException $e) {
        die("Error loading scheduling dataset: " . $e->getMessage());
    }
}

/**
 * Complete Global Waterfall Distribution Payment Handler
 */
/**
 * Complete Global Waterfall Distribution Payment Handler
 */
function handleApplyPayment($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $loan_id      = intval($_POST['loan_id'] ?? 0);
    $payment_amt  = floatval($_POST['payment_amount'] ?? 0);
    $remarks      = "Global Waterfall Repayment";

    if ($loan_id <= 0 || $payment_amt <= 0) {
        die("Validation Failed: Invalid Loan ID or Amount.");
    }

    try {
        $pdo->beginTransaction();

        // 1. Fetch all unpaid schedules
        $stmt = $pdo->prepare("SELECT * FROM loan_schedules WHERE loan_id = ? AND status != 'paid' ORDER BY due_date ASC FOR UPDATE");
        $stmt->execute([$loan_id]);
        $unpaid_schedules = $stmt->fetchAll();

        if (!$unpaid_schedules) {
            throw new Exception("No pending schedules found. Account may already be fully paid.");
        }

        $running_payment = round($payment_amt, 2);
        $totals = ['penalty' => 0, 'interest' => 0, 'principal' => 0];

        // 2. Waterfall Distribution
        foreach ($unpaid_schedules as $row) {
            if ($running_payment <= 0.005) break;

            $s_id = $row['id'];
            $rem = [
                'p' => round(floatval($row['rem_penalty']), 2),
                'i' => round(floatval($row['rem_interest']), 2),
                'pr' => round(floatval($row['rem_principal']), 2)
            ];

            // Allocate
            $alloc = ['p' => 0, 'i' => 0, 'pr' => 0];

            // Penalty -> Interest -> Principal
            foreach (['p' => 'penalty', 'i' => 'interest', 'pr' => 'principal'] as $k => $key) {
                if ($running_payment > 0 && $rem[$k] > 0) {
                    $take = min($running_payment, $rem[$k]);
                    $alloc[$k] = $take;
                    $rem[$k] -= $take;
                    $running_payment = round($running_payment - $take, 2);
                    $totals[$key] += $take;
                }
            }

            // Update row status
            $total_rem = $rem['p'] + $rem['i'] + $rem['pr'];
            $newStatus = ($total_rem <= 0.05) ? 'paid' : 'partial';

            $upd = $pdo->prepare("UPDATE loan_schedules SET rem_principal = ?, rem_interest = ?, rem_penalty = ?, status = ? WHERE id = ?");
            $upd->execute([$rem['pr'], $rem['i'], $rem['p'], $newStatus, $s_id]);
        }

        // 3. Log to ledger
        $excess = max(0, $running_payment);
        $stmtLedger = $pdo->prepare("INSERT INTO payment_ledger (loan_id, amount_paid, penalty_applied, interest_applied, principal_applied, excess, datetime, remarks) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
        $stmtLedger->execute([$loan_id, $payment_amt, $totals['penalty'], $totals['interest'], $totals['principal'], $excess, $remarks]);

        logSystemActivity($pdo, 'LOAN_PAYMENT', "Applied payment of {$payment_amt} to Loan ID #{$loan_id}");

        $check = $pdo->prepare("SELECT COUNT(*) FROM loan_schedules WHERE loan_id = ? AND status != 'paid'");
        $check->execute([$loan_id]);
        $remaining_count = intval($check->fetchColumn());

        if ($remaining_count === 0) {
            $pdo->prepare("UPDATE loans SET soa_status = 'Fully Paid' WHERE id = ?")->execute([$loan_id]);
            $_SESSION['success_message'] = "Payment processed. Loan is now Fully Paid!";
        } else {
            // Ensure status is NOT 'Fully Paid' if there is still debt
            $pdo->prepare("UPDATE loans SET soa_status = 'Active' WHERE id = ?")->execute([$loan_id]);
            $_SESSION['success_message'] = "Payment processed successfully.";
        }

        $pdo->commit();
        header("Location: index.php?route=view_loan&id=" . $loan_id);
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        die("Transaction Error: " . $e->getMessage());
    }
}

function handlePrintSOA($pdo)
{
    checkAuthenticated($pdo);
    $loan_id = intval($_GET['id'] ?? 0);
    if ($loan_id <= 0) die("Missing configuration index context.");

    $sqlLoan = "SELECT l.*, m.first_name, m.last_name, m.member_number 
                FROM loans l 
                JOIN members m ON l.member_id = m.id 
                WHERE l.id = ?";
    $stmtLoan = $pdo->prepare($sqlLoan);
    $stmtLoan->execute([$loan_id]);
    $loan = $stmtLoan->fetch();

    $schedule = $pdo->query("SELECT * FROM loan_schedules WHERE loan_id = {$loan_id} ORDER BY period ASC")->fetchAll();

    // Fetch ledger data to map to view expectation
    $ledger = $pdo->query("SELECT *, datetime AS date FROM payment_ledger WHERE loan_id = {$loan_id} ORDER BY id ASC")->fetchAll();

    $loanData = $loan;
    $rows     = $schedule;

    include dirname(__DIR__) . '/views/loan_print.php';
}

/**
 * Administrative balance adjustment override node
 */
function handleEditSchedulePeriod($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
            die("Security Guard Block: High level clearance parameters required.");
        }

        $schedule_id   = intval($_POST['schedule_id'] ?? 0);
        $loan_id       = intval($_POST['loan_id'] ?? 0);
        $rem_penalty   = floatval($_POST['rem_penalty'] ?? 0);

        try {
            // Simplified update just for manual penalty overrides from the view
            $sql = "UPDATE loan_schedules SET rem_penalty = ? WHERE id = ? AND loan_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$rem_penalty, $schedule_id, $loan_id]);

            $_SESSION['success_message'] = "Amortization period schedule metrics modified manually.";
            header("Location: index.php?route=view_loan&id=" . $loan_id);
            exit;
        } catch (PDOException $e) {
            die("Manual adjustments override database abort: " . $e->getMessage());
        }
    }
}

/**
 * Compiles all processing credit evaluations pending approvals
 */
function handlePendingLoansQueue($pdo)
{
    checkAuthenticated($pdo);

    if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
        die("Access Denied: You do not have permission to access the approval queue.");
    }

    $sql = "SELECT l.*, m.first_name, m.last_name, m.member_number 
            FROM loans l 
            JOIN members m ON l.member_id = m.id 
            WHERE l.loan_status = 'Pending' 
            ORDER BY l.id ASC";

    $pending_loans = $pdo->query($sql)->fetchAll();
    include dirname(__DIR__) . '/views/loan_pending.php';
}

/**
 * Handles workflow evaluation routing determinations (Approve / Reject)
 */
function handleProcessLoanApproval($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['role_id']) || intval($_SESSION['role_id']) !== 1) {
            die("Security Escalation Halted: Unauthorized credit line authorization request.");
        }

        $loan_id = intval($_POST['loan_id'] ?? 0);
        $action  = $_POST['action'] ?? 'Reject';

        try {
            $pdo->beginTransaction();

            $stmtLoan = $pdo->prepare("SELECT * FROM loans WHERE id = ? AND loan_status = 'Pending' FOR UPDATE");
            $stmtLoan->execute([$loan_id]);
            $loan = $stmtLoan->fetch();

            if (!$loan) {
                throw new Exception("The loan transaction trace was either processed already or deleted.");
            }

            if ($action === 'Approve') {
                // Ensure new loans start with an Active SOA Status
                $stmt = $pdo->prepare("UPDATE loans SET loan_status = 'Approved', soa_status = 'Active' WHERE id = ?");
                $stmt->execute([$loan_id]);

                $scheduleRows = AmortizationEngine::generateSchedule([
                    'principal'         => floatval($loan['principal']),
                    'interest_rate'     => floatval($loan['interest_rate']),
                    'terms'             => intval($loan['terms']),
                    'payment_frequency' => $loan['payment_frequency'],
                    'amortization_type' => $loan['amortization_type'],
                    'start_date'        => $loan['start_date'],
                    'manual_payment'    => floatval($loan['manual_payment'] ?? 0)
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

                logSystemActivity($pdo, 'LOAN_APPROVAL', "Approved and activated loan allocation record #{$loan_id}");
                $_SESSION['success_message'] = "Loan profile allocation approved, activated, and repayment matrix instantiated successfully.";
            } else {
                $stmt = $pdo->prepare("UPDATE loans SET loan_status = 'Rejected' WHERE id = ?");
                $stmt->execute([$loan_id]);

                logSystemActivity($pdo, 'LOAN_REJECTION', "Rejected loan application entry #{$loan_id}");
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
