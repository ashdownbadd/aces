<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

$route = $_GET['route'] ?? 'dashboard';

$content = '';

switch ($route) {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    case 'login':
        $content = handleLogin($pdo);
        break;

    case 'logout':
        handleLogout();
        break;

    case 'register':
        $content = handleRegistration($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    case 'dashboard':
        $content = handleDashboard($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Administrators
    |--------------------------------------------------------------------------
    */

    case 'admins':
        $content = handleAdminList($pdo);
        break;

    case 'toggle_status':
        handleToggleStatus($pdo);
        break;

    case 'toggle_role':
        handleToggleRole($pdo);
        break;

    case 'activity_logs':
        $content = handleActivityLogs($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Members
    |--------------------------------------------------------------------------
    */

    case 'members':
        $content = handleCoopMemberList($pdo);
        break;

    case 'add_member':
        $content = handleCreateCoopMember($pdo);
        break;

    case 'member_edit':
        $content = handleEditMember($pdo);
        break;

    case 'member_update':
        handleUpdateMember($pdo);
        break;

    case 'member_profile':
        $content = handleMemberProfile($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Ledger
    |--------------------------------------------------------------------------
    */

    case 'ledger':
        $content = handleLedgerDashboard($pdo);
        break;

    case 'add_ledger_entry':
        $content = handleCreateLedgerEntry($pdo);
        break;

    case 'ledger_statement':
        $content = handleLedgerStatement($pdo);
        break;

    case 'pending_approvals':
        $content = handlePendingApprovals($pdo);
        break;

    case 'approve_ledger_entry':
        handleApproveVoucher($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Loans
    |--------------------------------------------------------------------------
    */

    case 'amortization_dashboard':
        $content = handleAmortizationDashboard($pdo);
        break;

    case 'create_loan':
        $content = handleCreateLoan($pdo);
        break;

    case 'view_loan':
        $content = handleViewLoan($pdo);
        break;

    case 'apply_loan_payment':
        handleApplyPayment($pdo);
        break;

    case 'print_soa':
        handlePrintSOA($pdo);
        break;

    case 'edit_schedule_period':
        handleEditSchedulePeriod($pdo);
        break;

    case 'pending_loans_queue':
        $content = handlePendingLoansQueue($pdo);
        break;

    case 'process_loan_approval':
        handleProcessLoanApproval($pdo);
        break;

    /*
    |--------------------------------------------------------------------------
    | Default
    |--------------------------------------------------------------------------
    */

    default:
        abort(404, '404 - Route not found.');
}

/*
|--------------------------------------------------------------------------
| Render Layout
|--------------------------------------------------------------------------
*/

include __DIR__ . '/../views/layout.php';
