<?php
// Safely map the controller variable to our cleaner domain name
$admins = $members ?? [];
?>

<div class="container" style="font-family: Arial, sans-serif; padding: 20px;">
    <div style="float: right; text-align: right;">
        <p><a href="index.php?route=dashboard">← Back to Dashboard</a></p>
    </div>

    <h2>System Operators & Admin Directory</h2>
    <p>Reviewing all registered system administration access configurations, staff credentials, and platform access states.</p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green; font-weight: bold; background: #efe; padding: 8px; border: 1px solid green; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']); ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; font-weight: bold; background: #fee; padding: 8px; border: 1px solid red; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Operator ID</th>
                <th>Username Reference</th>
                <th>Email Address</th>
                <th>System Role Assignment</th>
                <th>Network Operational Status</th>
                <th>Administrative Actions Overrides</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($admins)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">No accounts found in the system matrix.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><code>#<?php echo htmlspecialchars($admin['id']); ?></code></td>
                        <td><strong><?php echo htmlspecialchars($admin['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($admin['email']); ?></td>
                        <td>
                            <?php if (intval($admin['role_id']) === 1 || $admin['role_name'] === 'Admin'): ?>
                                <span style="background: #d9534f; color: white; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 0.85em;">ADMINISTRATOR</span>
                            <?php else: ?>
                                <span style="background: #337ab7; color: white; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 0.85em;">STANDARD STAFF</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong style="color: <?php echo ($admin['status'] === 'active') ? 'green' : 'red'; ?>;">
                                ● <?php echo htmlspecialchars(ucfirst($admin['status'])); ?>
                            </strong>
                        </td>
                        <td>
                            <?php if ($admin['id'] === $_SESSION['user_id']): ?>
                                <span style="color: #999; font-style: italic;">Current Session (Self)</span>
                            <?php else: ?>
                                <div style="display: flex; gap: 8px;">
                                    <a href="index.php?route=toggle_status&id=<?php echo $admin['id']; ?>"
                                        onclick="return confirm('Are you sure you want to alter the operational status for this user?');"
                                        style="color: white; background: <?php echo ($admin['status'] === 'active') ? '#d9534f' : '#5cb85c'; ?>; font-weight: bold; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.85em;">
                                        <?php echo ($admin['status'] === 'active') ? 'Suspend Account 🛑' : 'Activate Account ✔'; ?>
                                    </a>

                                    <a href="index.php?route=toggle_role&id=<?php echo $admin['id']; ?>"
                                        onclick="return confirm('Change this user\'s administrative authorization rank?');"
                                        style="color: white; background: #f0ad4e; font-weight: bold; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.85em;">
                                        Toggle Role 🔄
                                    </a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>