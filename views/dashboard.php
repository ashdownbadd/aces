<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>
<div class="container">
    <div class="header-actions">
        <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong></p>
        <a href="index.php?route=logout" class="badge badge-admin" style="text-decoration:none;">Logout</a>
    </div>

    <h1>Welcome to the Dashboard</h1>
    <p>This is the central command center for the Membership, Amortization, and General Ledger modules.</p>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="alert alert-error">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <?php
    $alerts = (function_exists('getSystemAlerts') && isset($pdo))
        ? getSystemAlerts($pdo)
        : ['negative_equity' => [], 'past_due_loans' => []];
    ?>

    <?php if (!empty($alerts['negative_equity']) || !empty($alerts['past_due_loans'])): ?>
        <div class="alert alert-warning" style="padding: 20px; border-width: 2px;">
            <h2 style="margin-top: 0;">⚠️ System Health Alerts</h2>

            <?php if (!empty($alerts['negative_equity'])): ?>
                <p><strong>Negative Share Capital:</strong> <?php echo count($alerts['negative_equity']); ?> member(s) have a balance below zero and require review.</p>
            <?php endif; ?>

            <?php if (!empty($alerts['past_due_loans'])): ?>
                <p><strong>Past Due Loans:</strong> <?php echo count($alerts['past_due_loans']); ?> active loan(s) are past their scheduled due date.</p>
                <a href="index.php?route=amortization_dashboard" style="color: inherit; text-decoration: underline;">View Amortization Queue →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="dashboard-grid">
        <div class="card card-light">
            <h3>Total Members</h3>
            <p style="font-size: 2.5em; font-weight: bold; margin: 0;"><?php echo $total_members ?? 0; ?></p>
        </div>

        <div class="card card-blue">
            <h3 style="color: #0056b3;">Types</h3>
            <p>Regular: <strong><?php echo $types['Regular'] ?? 0; ?></strong></p>
            <p>Associate: <strong><?php echo $types['Associate'] ?? 0; ?></strong></p>
        </div>

        <div class="card card-green">
            <h3 style="color: #1e7e34;">Status</h3>
            <p>Active: <strong><?php echo $status['active'] ?? 0; ?></strong></p>
            <p>Inactive: <strong><?php echo $status['inactive'] ?? 0; ?></strong></p>
        </div>

        <div class="card card-purple">
            <h3 style="color: #6f42c1;">Gender</h3>
            <p>Male: <strong><?php echo $gender['Male'] ?? 0; ?></strong></p>
            <p>Female: <strong><?php echo $gender['Female'] ?? 0; ?></strong></p>
        </div>
    </div>

    <hr>

    <h3>Available Application Modules</h3>
    <ul style="list-style-type: none; padding-left: 0;">
        <li class="card card-light" style="margin-bottom: 15px;">
            <a href="index.php?route=members" style="font-weight: bold; font-size: 1.1em; text-decoration: none;">👥 Manage Cooperative Members Directory</a>
            <br><span style="color: #666; font-size: 0.95em;">Track official cooperative shareholders, initial capital logs, and statements.</span>
        </li>
        <li class="card card-light" style="margin-bottom: 15px;">
            <a href="index.php?route=amortization_dashboard" style="font-weight: bold; font-size: 1.1em; text-decoration: none;">📈 Amortization Calculators Module</a>
        </li>
        <li class="card card-light" style="margin-bottom: 15px;">
            <a href="index.php?route=ledger" style="font-weight: bold; font-size: 1.1em; text-decoration: none;">📖 General Ledger & Accounting Framework</a>
        </li>

        <?php if (isset($_SESSION['role_id']) && intval($_SESSION['role_id']) === 1): ?>
            <li class="card card-yellow" style="margin-top: 30px; border-style: dashed;">
                <a href="index.php?route=admins" style="font-weight: bold; font-size: 1.1em; color: #d32f2f; text-decoration: none;"><i class="fas fa-shield-halved"></i> Manage System Operators & Staff Control Panel</a>
                <br><span style="color: #555; font-size: 0.95em;">Administrative clearance node: modify access rankings, create credentials, or trigger operator locks.</span>
                <hr style="border-top-color: #f9a825;">
                <a href="index.php?route=activity_logs" style="font-weight: bold; font-size: 1.1em; color: #004d40; text-decoration: none;">📊 Review Global System Activity Logs</a>
            </li>
        <?php endif; ?>
    </ul>
</div>