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
        // 1. Fetch all unpaid schedule periods for active (Approved) loans
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

            // Check if the payment target timeline has passed today's date
            if ($row['due_date'] < $todayStr) {
                // Change state to overdue if it was still marked pending
                if ($status === 'pending') {
                    $status = 'overdue';
                    $isUpdated = true;
                }

                // Standardized late processing calculations: apply 1% monthly penalty on remaining principal balance
                // calculated daily (1% / 30 days) for accurate accrual trace
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
/**
 * Handles creation of a loan request; automatically saves under 'Pending' status
 */
function handleCreateLoan($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Extract inputs from the POST body
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

        // Validation safety checkpoint
        if ($member_id <= 0 || empty($loan_type) || $principal <= 0 || $interest_rate < 0 || $terms <= 0) {
            die("Validation Error: Please configure all required structural parameters correctly.");
        }

        try {
            // SQL Columns perfectly matched against your loans.sql structure
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

            $_SESSION['success_message'] = "Loan profile application successfully staged into the verification queue.";
            header("Location: index.php?route=pending_loans_queue");
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

        // --- ALIGN VARIABLE NAMES TO MATCH WHAT views/loan_view.php EXPECTS ---
        $loanData = $loan;      // view expects $loanData for name, member number, type
        $rows     = $schedule;  // view expects $rows for the schedule table loops
        $ledger   = [];         // view expects $ledger for transaction payments history (defaulting to empty array)

        include dirname(__DIR__) . '/views/loan_view.php';
    } catch (PDOException $e) {
        die("Error loading scheduling dataset: " . $e->getMessage());
    }
}

/**
 * Records financial amortization remittance rows and reduces outstanding balances
 */
function handleApplyPayment($pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $schedule_id  = intval($_POST['schedule_id'] ?? 0);
        $loan_id      = intval($_POST['loan_id'] ?? 0);
        $payment_amt  = floatval($_POST['payment_amount'] ?? 0);
        $payment_date = $_POST['payment_date'] ?? date('Y-m-d');

        if ($schedule_id <= 0 || $loan_id <= 0 || $payment_amt <= 0) {
            die("Error: Validation parameters trace missing for processing remittance.");
        }

        try {
            $pdo->beginTransaction();

            $stmtRow = $pdo->prepare("SELECT * FROM loan_schedules WHERE id = ? FOR UPDATE");
            $stmtRow->execute([$schedule_id]);
            $row = $stmtRow->fetch();

            if (!$row) {
                throw new Exception("Target loan repayment amortization matrix point missing.");
            }

            $rem_penalty   = floatval($row['rem_penalty']);
            $rem_interest  = floatval($row['rem_interest']);
            $rem_principal = floatval($row['rem_principal']);

            $allocated_penalty   = 0;
            $allocated_interest  = 0;
            $allocated_principal = 0;

            $running_payment = $payment_amt;

            if ($running_payment > 0 && $rem_penalty > 0) {
                if ($running_payment >= $rem_penalty) {
                    $allocated_penalty = $rem_penalty;
                    $running_payment -= $rem_penalty;
                    $rem_penalty = 0;
                } else {
                    $allocated_penalty = $running_payment;
                    $rem_penalty -= $running_payment;
                    $running_payment = 0;
                }
            }

            if ($running_payment > 0 && $rem_interest > 0) {
                if ($running_payment >= $rem_interest) {
                    $allocated_interest = $rem_interest;
                    $running_payment -= $rem_interest;
                    $rem_interest = 0;
                } else {
                    $allocated_interest = $running_payment;
                    $rem_interest -= $running_payment;
                    $running_payment = 0;
                }
            }

            if ($running_payment > 0 && $rem_principal > 0) {
                if ($running_payment >= $rem_principal) {
                    $allocated_principal = $rem_principal;
                    $running_payment -= $rem_principal;
                    $rem_principal = 0;
                } else {
                    $allocated_principal = $running_payment;
                    $rem_principal -= $running_payment;
                    $running_payment = 0;
                }
            }

            $newStatus = ($rem_principal <= 0 && $rem_interest <= 0) ? 'paid' : 'partial';

            $sqlUpdateSchedule = "UPDATE loan_schedules SET rem_principal = ?, rem_interest = ?, rem_penalty = ?, status = ? WHERE id = ?";
            $pdo->prepare($sqlUpdateSchedule)->execute([$rem_principal, $rem_interest, $rem_penalty, $newStatus, $schedule_id]);

            $sqlInsertHistory = "INSERT INTO loan_payments (
                                    schedule_id, loan_id, payment_amount, penalty_paid, interest_paid, principal_paid, payment_date
                                 ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $pdo->prepare($sqlInsertHistory)->execute([
                $schedule_id,
                $loan_id,
                $payment_amt,
                $allocated_penalty,
                $allocated_interest,
                $allocated_principal,
                $payment_date
            ]);

            $pdo->commit();
            $_SESSION['success_message'] = "Remittance applied successfully. Balances deducted.";
            header("Location: index.php?route=view_loan&id=" . $loan_id);
            exit;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            die("Remittance Transaction Failure: " . $e->getMessage());
        }
    }
}

/**
 * Formats data structure outputs into Statement of Account breakdowns
 */
function handlePrintSOA($pdo)
{
    checkAuthenticated($pdo);
    $loan_id = intval($_GET['id'] ?? 0);
    if ($loan_id <= 0) die("Missing configuration index context.");

    // 1. Fetch Loan Data
    $sqlLoan = "SELECT l.*, m.first_name, m.last_name, m.member_number 
                FROM loans l 
                JOIN members m ON l.member_id = m.id 
                WHERE l.id = ?";
    $stmtLoan = $pdo->prepare($sqlLoan);
    $stmtLoan->execute([$loan_id]);
    $loan = $stmtLoan->fetch();

    // 2. Fetch Schedule Data
    $schedule = $pdo->query("SELECT * FROM loan_schedules WHERE loan_id = {$loan_id} ORDER BY period ASC")->fetchAll();
    
    // 3. Fetch Ledger Data
    $ledger = $pdo->query("SELECT *, datetime AS date FROM payment_ledger WHERE loan_id = {$loan_id} ORDER BY id ASC")->fetchAll();
    
    // --- CRITICAL FIX: MAP DATA TO VIEW VARIABLES ---
    $loanData = $loan;
    $rows     = $schedule;
    // $ledger is already defined above

    // 4. Include the view
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
        $rem_principal = floatval($_POST['rem_principal'] ?? 0);
        $rem_interest  = floatval($_POST['rem_interest'] ?? 0);
        $rem_penalty   = floatval($_POST['rem_penalty'] ?? 0);
        $status        = trim($_POST['status'] ?? 'pending');

        try {
            $sql = "UPDATE loan_schedules SET rem_principal = ?, rem_interest = ?, rem_penalty = ?, status = ? WHERE id = ? AND loan_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$rem_principal, $rem_interest, $rem_penalty, $status, $schedule_id, $loan_id]);

            $_SESSION['success_message'] = "Amortization period schedule criteria metrics modified manually.";
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
                $stmt = $pdo->prepare("UPDATE loans SET loan_status = 'Approved' WHERE id = ?");
                $stmt->execute([$loan_id]);

                // FIXED: Changed keys to match your database schema columns exactly
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

                // FIXED: Removed backslashes and utilized single quotes for nested parameters
                logSystemActivity($pdo, 'LOAN_APPROVAL', "Approved and activated loan allocation record #{$loan_id}");

                $_SESSION['success_message'] = "Loan profile allocation approved, activated, and repayment matrix instantiated successfully.";
            } else {
                $stmt = $pdo->prepare("UPDATE loans SET loan_status = 'Rejected' WHERE id = ?");
                $stmt->execute([$loan_id]);

                // FIXED: Removed backslashes and utilized single quotes for nested parameters
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
