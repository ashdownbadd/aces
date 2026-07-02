<?php
$admins = $members ?? [];
?>
<div class="container">
    <div class="header-actions">
        <p><a href="index.php?route=dashboard">← Back to Dashboard</a></p>
    </div>

    <h2>System Operators & Admin Directory</h2>
    <p>Reviewing all registered system administration access configurations, staff credentials, and platform access states.</p>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']);
                                        unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error_message']);
                                        unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
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
                    <td colspan="6" style="text-align: center;">No accounts found in the system matrix.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><code>#<?php echo htmlspecialchars($admin['id']); ?></code></td>
                        <td><strong><?php echo htmlspecialchars($admin['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($admin['email']); ?></td>
                        <td>
                            <?php if (intval($admin['role_id']) === 1 || $admin['role_name'] === 'Admin'): ?>
                                <span class="badge badge-admin">ADMINISTRATOR</span>
                            <?php else: ?>
                                <span class="badge badge-staff">STANDARD STAFF</span>
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
                                        onclick="return confirm('Are you sure?');"
                                        class="btn <?php echo ($admin['status'] === 'active') ? 'btn-danger' : 'btn-success'; ?>">

                                        <?php if ($admin['status'] === 'active'): ?>
                                            <i class="fas fa-ban"></i> Suspend
                                        <?php else: ?>
                                            <i class="fas fa-check"></i> Activate
                                        <?php endif; ?>
                                    </a>

                                    <a href="index.php?route=toggle_role&id=<?php echo $admin['id']; ?>"
                                        class="btn btn-warning">
                                        <i class="fas fa-sync-alt"></i> Toggle Role
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