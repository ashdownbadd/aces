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
        <p style="color: green; font-weight: bold; background: #efe; padding: 8px; border: 1px solid green; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']); ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; font-weight: bold; background: #fee; padding: 8px; border: 1px solid red; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Username</th>
                <th>Email Address</th>
                <th>System Access Level</th>
                <th>Status</th>
                <th>Available Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($admins)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #777;">No backend operators registered in the database.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($admin['id']); ?></td>
                        <td><strong><?php echo htmlspecialchars($admin['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($admin['email']); ?></td>
                        <td>
                            <span style="background: #eef; padding: 2px 6px; border-radius: 4px; font-size: 0.9em;">
                                <?php echo htmlspecialchars($admin['role_name']); ?>
                            </span>
                        </td>
                        <td>
                            <strong style="color: <?php echo ($admin['status'] === 'active') ? 'green' : 'red'; ?>;">
                                <?php echo htmlspecialchars(ucfirst($admin['status'])); ?>
                            </strong>
                        </td>
                        <td>
                            <?php if ($admin['id'] === $_SESSION['user_id']): ?>
                                <span style="color: #999; font-style: italic;">Current Session</span>
                            <?php elseif (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2 && $admin['role_name'] === 'Admin'): ?>
                                <span style="color: #999; font-style: italic;">Protected (Admin)</span>
                            <?php else: ?>
                                <a href="index.php?route=toggle_status&id=<?php echo $admin['id']; ?>"
                                    onclick="return confirm('Are you sure you want to alter the operational status for this user?');"
                                    style="color: <?php echo ($admin['status'] === 'active') ? '#d9534f' : '#5cb85c'; ?>; font-weight: bold; text-decoration: none;">
                                    <?php echo ($admin['status'] === 'active') ? 'Suspend Account' : 'Activate Account'; ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>