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

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px;">
        <div style="background: #f4f4f4; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3>Total Members</h3>
            <p style="font-size: 2em; font-weight: bold; margin: 0;"><?php echo $total_members ?? 0; ?></p>
        </div>
        
        <div style="background: #eef7ff; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3>Types</h3>
            <p style="margin: 5px 0;">Regular: <?php echo $types['Regular'] ?? 0; ?></p>
            <p style="margin: 5px 0;">Associate: <?php echo $types['Associate'] ?? 0; ?></p>
        </div>

        <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3>Status</h3>
            <p style="margin: 5px 0;">Active: <?php echo $status['active'] ?? 0; ?></p>
            <p style="margin: 5px 0;">Inactive: <?php echo $status['inactive'] ?? 0; ?></p>
            <p style="margin: 5px 0;">Deceased: <?php echo $status['deceased'] ?? 0; ?></p>
        </div>

        <div style="background: #f3e5f5; padding: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <h3>Gender</h3>
            <p style="margin: 5px 0;">Male: <?php echo $gender['Male'] ?? 0; ?></p>
            <p style="margin: 5px 0;">Female: <?php echo $gender['Female'] ?? 0; ?></p>
        </div>
    </div>

    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">

    <h3>Available Application Modules</h3>
    <ul>
        <li style="margin-bottom: 10px;">
            <a href="index.php?route=members" style="font-weight: bold; color: #337ab7; text-decoration: none;">Manage Cooperative Members Directory</a>
            <br><span style="color: #666; font-size: 0.9em;">(Track official cooperative shareholders, initial capital logs, and statements)</span>
        </li>
        <li style="margin-bottom: 10px;">
            <a href="index.php?route=amortization_dashboard" style="font-weight: bold; color: #337ab7; text-decoration: none;">Amortization Calculators Module</a>
        </li>
        <li style="margin-bottom: 10px;">
            <a href="index.php?route=ledger" style="font-weight: bold; color: #337ab7; text-decoration: none;">General Ledger & Accounting Framework</a>
        </li>

        <?php if (isset($_SESSION['role_id']) && intval($_SESSION['role_id']) === 1): ?>
            <li style="margin-bottom: 10px; padding: 10px; background: #fffde7; border: 1px dashed #fbc02d; border-radius: 6px;">
                <a href="index.php?route=admins" style="font-weight: bold; color: #cc1111; text-decoration: none;">🛡️ Manage System Operators & Staff Control Panel</a>
                <br><span style="color: #555; font-size: 0.9em;">(Administrative clearance node: modify access rankings, create credentials, or trigger operator locks)</span>
                
                <br><br>
                📊 <a href="index.php?route=activity_logs" style="font-weight: bold; color: #004d40; text-decoration: none;">Review Global System Activity Logs</a>
                <br><span style="color: #555; font-size: 0.9em;">(View an absolute timeline trail of what actions staff and admins have committed)</span>
            </li>
        <?php endif; ?>
    </ul>
</div>