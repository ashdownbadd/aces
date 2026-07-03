<?php
// controllers/AmortizationController.php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

require_once __DIR__ . '/../helpers/AmortizationEngine.php';

/**
 * Automatically updates overdue loan schedules and recalculates penalties.
 */
function checkAndUpdateOverdueSchedules(PDO $pdo): void
{
    try {

        $stmt = $pdo->query("
            SELECT
                ls.*,
                l.loan_status
            FROM loan_schedules ls
            INNER JOIN loans l
                ON ls.loan_id = l.id
            WHERE
                ls.status <> 'paid'
                AND l.loan_status = 'Approved'
        ");

        $schedules = $stmt->fetchAll();

        $today = date('Y-m-d');

        $update = $pdo->prepare("
            UPDATE loan_schedules
            SET
                status = ?,
                rem_penalty = ?
            WHERE id = ?
        ");

        foreach ($schedules as $schedule) {

            $status = $schedule['status'];
            $penalty = (float) $schedule['rem_penalty'];
            $changed = false;

            if ($schedule['due_date'] < $today) {

                if ($status === 'pending') {
                    $status = 'overdue';
                    $changed = true;
                }

                $daysOverdue = (
                    strtotime($today) -
                    strtotime($schedule['due_date'])
                ) / 86400;

                if ($daysOverdue > 0) {

                    $computedPenalty =
                        (float) $schedule['rem_principal']
                        * (0.01 / 30)
                        * $daysOverdue;

                    if ($computedPenalty > $penalty) {
                        $penalty = $computedPenalty;
                        $changed = true;
                    }
                }
            }

            if ($changed) {

                $update->execute([
                    $status,
                    $penalty,
                    $schedule['id']
                ]);
            }
        }
    } catch (Throwable $e) {

        error_log(
            'Loan schedule automation failed: ' .
                $e->getMessage()
        );
    }
}

/**
 * Displays the amortization dashboard.
 */
function handleAmortizationDashboard(PDO $pdo): string
{
    checkAuthenticated($pdo);

    checkAndUpdateOverdueSchedules($pdo);

    $stmt = $pdo->query("
        SELECT
            l.*,
            m.member_number,
            m.first_name,
            m.last_name
        FROM loans l
        INNER JOIN members m
            ON l.member_id = m.id
        WHERE l.loan_status = 'Approved'
        ORDER BY l.id DESC
    ");

    return render('amortization_dashboard', [
        'loans' => $stmt->fetchAll()
    ]);
}

/**
 * Creates a new loan application.
 */
function handleCreateLoan(PDO $pdo): string
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $member_id         = (int) ($_POST['member_id'] ?? 0);
        $loan_type         = trim($_POST['loan_type'] ?? '');
        $collateral        = trim($_POST['collateral'] ?? '');
        $amortization_type = trim($_POST['amortization_type'] ?? 'Diminishing Balance');
        $payment_frequency = trim($_POST['payment_frequency'] ?? 'Monthly');

        $principal         = (float) ($_POST['principal'] ?? 0);
        $interest_rate     = (float) ($_POST['interest_rate'] ?? 0);
        $terms             = (int) ($_POST['terms'] ?? 0);

        $start_date        = $_POST['start_date'] ?? date('Y-m-d');
        $manual_payment    = (float) ($_POST['manual_payment'] ?? 0);

        if (
            $member_id <= 0 ||
            empty($loan_type) ||
            $principal <= 0 ||
            $terms <= 0 ||
            $interest_rate < 0
        ) {

            $_SESSION['error_message'] =
                'Please complete all required loan information.';

            header('Location: index.php?route=create_loan');
            exit;
        }

        try {

            $stmt = $pdo->prepare("
                INSERT INTO loans
                (
                    member_id,
                    loan_type,
                    collateral,
                    amortization_type,
                    payment_frequency,
                    principal,
                    interest_rate,
                    terms,
                    start_date,
                    manual_payment,
                    loan_status
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending'
                )
            ");

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

            logSystemActivity(
                $pdo,
                'LOAN_CREATED',
                "Created loan application for member #{$member_id}"
            );

            $_SESSION['success_message'] =
                'Loan application submitted successfully.';

            if (
                isset($_SESSION['role_id']) &&
                (int) $_SESSION['role_id'] === 1
            ) {

                header('Location: index.php?route=pending_loans_queue');
            } else {

                header('Location: index.php?route=amortization_dashboard');
            }

            exit;
        } catch (PDOException $e) {

            $_SESSION['error_message'] =
                'Database error: ' . $e->getMessage();

            header('Location: index.php?route=create_loan');
            exit;
        }
    }

    $stmt = $pdo->query("
        SELECT
            id,
            member_number,
            first_name,
            last_name
        FROM members
        ORDER BY last_name ASC
    ");

    return render('loan_create', [
        'members' => $stmt->fetchAll()
    ]);
}

/**
 * Displays a complete loan profile with its amortization schedule
 * and payment history.
 */
function handleViewLoan(PDO $pdo): string
{
    checkAuthenticated($pdo);

    $loan_id = (int) ($_GET['id'] ?? 0);

    if ($loan_id <= 0) {
        $_SESSION['error_message'] = 'Invalid loan selected.';
        header('Location: index.php?route=amortization_dashboard');
        exit;
    }

    try {

        $stmtLoan = $pdo->prepare("
            SELECT
                l.*,
                m.member_number,
                m.first_name,
                m.last_name
            FROM loans l
            INNER JOIN members m
                ON l.member_id = m.id
            WHERE l.id = ?
            LIMIT 1
        ");

        $stmtLoan->execute([$loan_id]);

        $loan = $stmtLoan->fetch();

        if (!$loan) {

            $_SESSION['error_message'] = 'Loan record not found.';
            header('Location: index.php?route=amortization_dashboard');
            exit;
        }

        $stmtSchedule = $pdo->prepare("
            SELECT *
            FROM loan_schedules
            WHERE loan_id = ?
            ORDER BY period ASC
        ");

        $stmtSchedule->execute([$loan_id]);

        $schedule = $stmtSchedule->fetchAll();

        $stmtLedger = $pdo->prepare("
            SELECT
                *,
                datetime
            FROM payment_ledger
            WHERE loan_id = ?
            ORDER BY id DESC
        ");

        $stmtLedger->execute([$loan_id]);

        $ledger = $stmtLedger->fetchAll();

        return render('loan_view', [

            'loan'      => $loan,
            'loanData'  => $loan,
            'schedule'  => $schedule,
            'rows'      => $schedule,
            'ledger'    => $ledger

        ]);
    } catch (PDOException $e) {

        $_SESSION['error_message'] =
            'Unable to load loan details.';

        header('Location: index.php?route=amortization_dashboard');
        exit;
    }
}

/**
 * Generates the printable Statement of Account.
 */
function handlePrintSOA(PDO $pdo)
{
    checkAuthenticated($pdo);

    $loanId = (int) ($_GET['id'] ?? 0);

    if ($loanId <= 0) {
        $_SESSION['error_message'] = 'Invalid loan selected.';
        header('Location: index.php?route=amortization_dashboard');
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT
            l.*,
            m.first_name,
            m.last_name,
            m.member_number
        FROM loans l
        JOIN members m
            ON l.member_id = m.id
        WHERE l.id = ?
    ");

    $stmt->execute([$loanId]);

    $loanData = $stmt->fetch();

    if (!$loanData) {
        $_SESSION['error_message'] = 'Loan not found.';
        header('Location: index.php?route=amortization_dashboard');
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT *
        FROM loan_schedules
        WHERE loan_id = ?
        ORDER BY period
    ");

    $stmt->execute([$loanId]);

    $rows = $stmt->fetchAll();

    $stmt = $pdo->prepare("
        SELECT
            *,
            datetime AS date
        FROM payment_ledger
        WHERE loan_id = ?
        ORDER BY id
    ");

    $stmt->execute([$loanId]);

    $ledger = $stmt->fetchAll();

    return render('loan_print', compact(
        'loanData',
        'rows',
        'ledger'
    ));
}

/**
 * Allows an administrator to manually adjust a schedule penalty.
 */
function handleEditSchedulePeriod(PDO $pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?route=amortization_dashboard');
        exit;
    }

    if ((int) ($_SESSION['role_id'] ?? 0) !== 1) {
        $_SESSION['error_message'] = 'Administrator privileges required.';
        header('Location: index.php?route=amortization_dashboard');
        exit;
    }

    $scheduleId = (int) ($_POST['schedule_id'] ?? 0);
    $loanId = (int) ($_POST['loan_id'] ?? 0);
    $penalty = (float) ($_POST['rem_penalty'] ?? 0);

    try {

        $stmt = $pdo->prepare("
            UPDATE loan_schedules
            SET rem_penalty = ?
            WHERE id = ?
              AND loan_id = ?
        ");

        $stmt->execute([
            $penalty,
            $scheduleId,
            $loanId
        ]);

        $_SESSION['success_message'] = 'Penalty updated successfully.';
    } catch (PDOException $e) {

        $_SESSION['error_message'] = $e->getMessage();
    }

    header("Location: index.php?route=view_loan&id={$loanId}");
    exit;
}

/**
 * Displays all pending loan applications awaiting approval.
 */
function handlePendingLoansQueue(PDO $pdo)
{
    checkAuthenticated($pdo);

    if ((int) ($_SESSION['role_id'] ?? 0) !== 1) {

        $_SESSION['error_message'] =
            'Administrator privileges required.';

        header('Location: index.php?route=dashboard');
        exit;
    }

    $stmt = $pdo->query("
        SELECT
            l.*,
            m.first_name,
            m.last_name,
            m.member_number
        FROM loans l
        JOIN members m
            ON l.member_id = m.id
        WHERE l.loan_status='Pending'
        ORDER BY l.id
    ");

    $pending_loans = $stmt->fetchAll();

    return render('loan_pending', compact(
        'pending_loans'
    ));
}

/**
 * Approves or rejects a pending loan application.
 */
function handleProcessLoanApproval(PDO $pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?route=pending_loans_queue');
        exit;
    }

    if ((int) ($_SESSION['role_id'] ?? 0) !== 1) {

        $_SESSION['error_message'] =
            'Administrator privileges required.';

        header('Location: index.php?route=dashboard');
        exit;
    }

    $loanId = (int) ($_POST['loan_id'] ?? 0);
    $action = $_POST['action'] ?? 'Reject';

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            SELECT *
            FROM loans
            WHERE id = ?
              AND loan_status='Pending'
            FOR UPDATE
        ");

        $stmt->execute([$loanId]);

        $loan = $stmt->fetch();

        if (!$loan) {
            throw new Exception('Loan no longer exists.');
        }

        if ($action === 'Approve') {

            $stmt = $pdo->prepare("
                UPDATE loans
                SET
                    loan_status='Approved',
                    soa_status='Active'
                WHERE id=?
            ");

            $stmt->execute([$loanId]);

            $schedule = AmortizationEngine::generateSchedule([
                'principal' => (float) $loan['principal'],
                'interest_rate' => (float) $loan['interest_rate'],
                'terms' => (int) $loan['terms'],
                'payment_frequency' => $loan['payment_frequency'],
                'amortization_type' => $loan['amortization_type'],
                'start_date' => $loan['start_date'],
                'manual_payment' => (float) $loan['manual_payment']
            ]);

            $insert = $pdo->prepare("
                INSERT INTO loan_schedules
                (
                    loan_id,
                    period,
                    due_date,
                    principal,
                    interest,
                    rem_principal,
                    rem_interest,
                    rem_penalty,
                    status,
                    remarks
                )
                VALUES
                (
                    ?,?,?,?,?,?,
                    ?,0,'pending',''
                )
            ");

            foreach ($schedule as $row) {

                $insert->execute([
                    $loanId,
                    $row['period'],
                    $row['due_date'],
                    $row['principal'],
                    $row['interest'],
                    $row['principal'],
                    $row['interest']
                ]);
            }

            logSystemActivity(
                $pdo,
                'LOAN_APPROVAL',
                "Approved Loan #{$loanId}"
            );

            $_SESSION['success_message'] =
                'Loan approved successfully.';
        } else {

            $stmt = $pdo->prepare("
                UPDATE loans
                SET loan_status='Rejected'
                WHERE id=?
            ");

            $stmt->execute([$loanId]);

            logSystemActivity(
                $pdo,
                'LOAN_REJECTION',
                "Rejected Loan #{$loanId}"
            );

            $_SESSION['success_message'] =
                'Loan rejected successfully.';
        }

        $pdo->commit();
    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $_SESSION['error_message'] =
            $e->getMessage();
    }

    header('Location: index.php?route=pending_loans_queue');
    exit;
}

function handleApplyPayment(PDO $pdo)
{
    checkAuthenticated($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?route=amortization_dashboard');
        exit;
    }

    $loanId = (int) ($_POST['loan_id'] ?? 0);
    $paymentAmount = (float) ($_POST['payment_amount'] ?? 0);

    if ($loanId <= 0 || $paymentAmount <= 0) {
        $_SESSION['error_message'] = 'Invalid payment information.';
        header("Location: index.php?route=view_loan&id={$loanId}");
        exit;
    }

    try {

        $pdo->beginTransaction();

        /*
        |--------------------------------------------------------------------------
        | Lock all unpaid schedules
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT *
            FROM loan_schedules
            WHERE loan_id = ?
              AND status <> 'paid'
            ORDER BY due_date ASC
            FOR UPDATE
        ");

        $stmt->execute([$loanId]);

        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$schedules) {
            throw new Exception('Loan is already fully paid.');
        }

        $remainingPayment = round($paymentAmount, 2);

        $allocatedPenalty = 0;
        $allocatedInterest = 0;
        $allocatedPrincipal = 0;

        /*
        |--------------------------------------------------------------------------
        | Waterfall Allocation
        |--------------------------------------------------------------------------
        */

        foreach ($schedules as $schedule) {

            if ($remainingPayment <= 0) {
                break;
            }

            $remainingPenalty = (float) $schedule['rem_penalty'];
            $remainingInterest = (float) $schedule['rem_interest'];
            $remainingPrincipal = (float) $schedule['rem_principal'];

            /*
            | Penalty
            */

            if ($remainingPenalty > 0 && $remainingPayment > 0) {

                $amount = min($remainingPenalty, $remainingPayment);

                $remainingPenalty -= $amount;
                $remainingPayment -= $amount;

                $allocatedPenalty += $amount;
            }

            /*
            | Interest
            */

            if ($remainingInterest > 0 && $remainingPayment > 0) {

                $amount = min($remainingInterest, $remainingPayment);

                $remainingInterest -= $amount;
                $remainingPayment -= $amount;

                $allocatedInterest += $amount;
            }

            /*
            | Principal
            */

            if ($remainingPrincipal > 0 && $remainingPayment > 0) {

                $amount = min($remainingPrincipal, $remainingPayment);

                $remainingPrincipal -= $amount;
                $remainingPayment -= $amount;

                $allocatedPrincipal += $amount;
            }

            $status =
                ($remainingPenalty + $remainingInterest + $remainingPrincipal <= 0.01)
                ? 'paid'
                : 'partial';

            $update = $pdo->prepare("
                UPDATE loan_schedules
                SET
                    rem_penalty = ?,
                    rem_interest = ?,
                    rem_principal = ?,
                    status = ?
                WHERE id = ?
            ");

            $update->execute([
                $remainingPenalty,
                $remainingInterest,
                $remainingPrincipal,
                $status,
                $schedule['id']
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Ledger
        |--------------------------------------------------------------------------
        */

        $ledger = $pdo->prepare("
            INSERT INTO payment_ledger
            (
                loan_id,
                amount_paid,
                penalty_applied,
                interest_applied,
                principal_applied,
                excess,
                datetime,
                remarks
            )
            VALUES
            (
                ?,?,?,?,?,?,
                NOW(),
                ?
            )
        ");

        $ledger->execute([
            $loanId,
            $paymentAmount,
            $allocatedPenalty,
            $allocatedInterest,
            $allocatedPrincipal,
            max(0, $remainingPayment),
            'Global Waterfall Repayment'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Loan Status
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM loan_schedules
            WHERE loan_id = ?
              AND status <> 'paid'
        ");

        $stmt->execute([$loanId]);

        $remainingSchedules = (int) $stmt->fetchColumn();

        $loanStatus = ($remainingSchedules === 0)
            ? 'Fully Paid'
            : 'Active';

        $stmt = $pdo->prepare("
            UPDATE loans
            SET soa_status = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $loanStatus,
            $loanId
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        logSystemActivity(
            $pdo,
            'LOAN_PAYMENT',
            "Applied payment of {$paymentAmount} to Loan #{$loanId}"
        );

        $pdo->commit();

        $_SESSION['success_message'] =
            ($remainingSchedules === 0)
            ? 'Loan fully paid successfully.'
            : 'Payment applied successfully.';
    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $_SESSION['error_message'] =
            'Unable to process payment: ' . $e->getMessage();
    }

    header("Location: index.php?route=view_loan&id={$loanId}");
    exit;
}
