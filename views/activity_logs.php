<?php
// views/activity_logs.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="float: right; text-align: right;">
        <p><a href="index.php?route=dashboard">← Back to Dashboard</a></p>
    </div>

    <h2>🛡️ System Security Audit Logs</h2>
    <p>Reviewing recent automated administrative task updates, security authorizations, loan allocations, and operational event triggers.</p>

    <?php if (empty($logs)): ?>
        <div style="background: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; padding: 15px; border-radius: 4px;">
            No audit records or activity history items found in the logging dataset.
        </div>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 20px; text-align: left;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="width: 8%;">Log ID</th>
                    <th style="width: 12%;">Operator</th>
                    <th style="width: 15%;">Action Node</th>
                    <th>Activity Details</th>
                    <th style="width: 15%;">IP Address</th>
                    <th style="width: 18%;">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><strong>#<?php echo htmlspecialchars($log['id']); ?></strong></td>
                        <td>
                            <span style="color: #337ab7; font-weight: bold;">
                                <?php echo htmlspecialchars($log['username'] ?? 'System'); ?>
                            </span>
                            <br><small style="color: #666;">UID: <?php echo htmlspecialchars($log['user_id'] ?? 'N/A'); ?></small>
                        </td>
                        <td>
                            <span style="background: #eef7ff; color: #31708f; padding: 3px 8px; border-radius: 3px; font-family: monospace; font-size: 0.9em; font-weight: bold; border: 1px solid #bce8f1;">
                                <?php echo htmlspecialchars($log['action']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($log['details']); ?></td>
                        <td><small style="font-family: monospace;"><?php echo htmlspecialchars($log['ip_address'] ?? '0.0.0.0'); ?></small></td>
                        <td><small style="color: #555;"><?php echo htmlspecialchars($log['created_at'] ?? 'Unknown'); ?></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>