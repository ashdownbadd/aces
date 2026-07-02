<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="float: right; text-align: right;">
        <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong></p>
        <a href="index.php?route=logout" style="color: red; font-weight: bold;">Logout</a>
    </div>

    <h1>Welcome to the Dashboard</h1>
    <p>This is the central command center for the Membership, Amortization, and General Ledger modules.</p>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; font-weight: bold; background: #fee; padding: 10px; border: 1px solid red; margin-bottom: 20px; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <?php
    // Safely fetch alerts if the function and database connection exist
    $alerts = (function_exists('getSystemAlerts') && isset($pdo)) 
        ? getSystemAlerts($pdo) 
        : ['negative_equity' => [], 'past_due_loans' => []];
    ?>

    <?php if (!empty($alerts['negative_equity']) || !empty($alerts['past_due_loans'])): ?>
        <div style="background: #fff3f3; border: 2px solid #ffcdd2; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h2 style="color: #c62828; margin-top: 0; font-size: 1.4em;">⚠️ System Health Alerts</h2>
            
            <?php if (!empty($alerts['negative_equity'])): ?>
                <p style="margin: 5px 0 10px 0; color: #b71c1c; font-size: 1.05em;">
                    <strong>Negative Share Capital:</strong> 
                    <?php echo count($alerts['negative_equity']); ?> member(s) have a balance below zero and require review.
                </p>
            <?php endif; ?>

            <?php if (!empty($alerts['past_due_loans'])): ?>
                <p style="margin: 5px 0 10px 0; color: #b71c1c; font-size: 1.05em;">
                    <strong>Past Due Loans:</strong> 
                    <?php echo count($alerts['past_due_loans']); ?> active loan(s) are past their scheduled due date.
                </p>
                <a href="index.php?route=amortization_dashboard" style="color: #c62828; font-weight: bold; text-decoration: none; border-bottom: 1px solid #c62828;">
                    View Amortization Queue →
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px;">
        <div style="background: #f4f4f4; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3 style="margin-top: 0;">Total Members</h3>
            <p style="font-size: 2.5em; font-weight: bold; margin: 0; color: #333;"><?php echo $total_members ?? 0; ?></p>
        </div>
        
        <div style="background: #eef7ff; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3 style="margin-top: 0; color: #0056b3;">Types</h3>
            <p style="margin: 5px 0; font-size: 1.1em;">Regular: <strong><?php echo $types['Regular'] ?? 0; ?></strong></p>
            <p style="margin: 5px 0; font-size: 1.1em;">Associate: <strong><?php echo $types['Associate'] ?? 0; ?></strong></p>
        </div>

        <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3 style="margin-top: 0; color: #1e7e34;">Status</h3>
            <p style="margin: 5px 0; font-size: 1.1em;">Active: <strong><?php echo $status['active'] ?? 0; ?></strong></p>
            <p style="margin: 5px 0; font-size: 1.1em;">Inactive: <strong><?php echo $status['inactive'] ?? 0; ?></strong></p>
            <p style="margin: 5px 0; font-size: 1.1em;">Deceased: <strong><?php echo $status['deceased'] ?? 0; ?></strong></p>
        </div>

        <div style="background: #f3e5f5; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3 style="margin-top: 0; color: #6f42c1;">Gender</h3>
            <p style="margin: 5px 0; font-size: 1.1em;">Male: <strong><?php echo $gender['Male'] ?? 0; ?></strong></p>
            <p style="margin: 5px 0; font-size: 1.1em;">Female: <strong><?php echo $gender['Female'] ?? 0; ?></strong></p>
        </div>
    </div>

    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">

    <h3>Available Application Modules</h3>
    <ul style="list-style-type: none; padding-left: 0;">
        <li style="margin-bottom: 15px; padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 6px;">
            <a href="index.php?route=members" style="font-weight: bold; font-size: 1.1em; color: #337ab7; text-decoration: none;">👥 Manage Cooperative Members Directory</a>
            <br><span style="color: #666; font-size: 0.95em; display: inline-block; margin-top: 5px;">Track official cooperative shareholders, initial capital logs, and statements.</span>
        </li>
        <li style="margin-bottom: 15px; padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 6px;">
            <a href="index.php?route=amortization_dashboard" style="font-weight: bold; font-size: 1.1em; color: #337ab7; text-decoration: none;">📈 Amortization Calculators Module</a>
        </li>
        <li style="margin-bottom: 15px; padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 6px;">
            <a href="index.php?route=ledger" style="font-weight: bold; font-size: 1.1em; color: #337ab7; text-decoration: none;">📖 General Ledger & Accounting Framework</a>
        </li>

        <?php if (isset($_SESSION['role_id']) && intval($_SESSION['role_id']) === 1): ?>
            <li style="margin-top: 30px; margin-bottom: 10px; padding: 15px; background: #fffde7; border: 1px dashed #fbc02d; border-radius: 6px;">
                <a href="index.php?route=admins" style="font-weight: bold; font-size: 1.1em; color: #d32f2f; text-decoration: none;">🛡️ Manage System Operators & Staff Control Panel</a>
                <br><span style="color: #555; font-size: 0.95em; display: inline-block; margin-top: 5px;">Administrative clearance node: modify access rankings, create credentials, or trigger operator locks.</span>
                
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f9a825;">
                    <a href="index.php?route=activity_logs" style="font-weight: bold; font-size: 1.1em; color: #004d40; text-decoration: none;">📊 Review Global System Activity Logs</a>
                    <br><span style="color: #555; font-size: 0.95em; display: inline-block; margin-top: 5px;">View an absolute timeline trail of what actions staff and admins have committed.</span>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</div>