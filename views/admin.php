<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

$admins = $members ?? [];
?>

<div class="container">

    <div class="header-actions">
        <p>
            <a href="index.php?route=dashboard">
                ← Back to Dashboard
            </a>
        </p>
    </div>

    <h2>System Operators &amp; Admin Directory</h2>

    <p>
        Reviewing all registered system administration access configurations,
        staff credentials, and platform access states.
    </p>

    <?php if (isset($_SESSION['success_message'])): ?>

        <div class="alert alert--success">

            <?= htmlspecialchars($_SESSION['success_message']); ?>

            <?php unset($_SESSION['success_message']); ?>

        </div>

    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>

        <div class="alert alert--danger">

            <?= htmlspecialchars($_SESSION['error_message']); ?>

            <?php unset($_SESSION['error_message']); ?>

        </div>

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

                    <td colspan="6" class="table__empty">

                        No accounts found in the system matrix.

                    </td>

                </tr>

            <?php else: ?>

                <?php foreach ($admins as $admin): ?>

                    <tr>

                        <td>

                            <code class="code">

                                #<?= htmlspecialchars($admin['id']); ?>

                            </code>

                        </td>

                        <td>

                            <strong>

                                <?= htmlspecialchars($admin['username']); ?>

                            </strong>

                        </td>

                        <td>

                            <?= htmlspecialchars($admin['email']); ?>

                        </td>

                        <td>

                            <?php if (
                                intval($admin['role_id']) === 1 ||
                                $admin['role_name'] === 'Admin'
                            ): ?>

                                <span class="badge badge-admin">

                                    ADMINISTRATOR

                                </span>

                            <?php else: ?>

                                <span class="badge badge-staff">

                                    STANDARD STAFF

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if ($admin['status'] === 'active'): ?>

                                <span class="status status--active">

                                    ● Active

                                </span>

                            <?php else: ?>

                                <span class="status status--inactive">

                                    ● Inactive

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if ($admin['id'] == $_SESSION['user_id']): ?>

                                <span class="status status--self">

                                    Current Session (Self)

                                </span>

                            <?php else: ?>

                                <div class="action-group">

                                    <a
                                        href="index.php?route=toggle_status&id=<?= $admin['id']; ?>"
                                        class="btn <?= ($admin['status'] === 'active') ? 'btn--danger' : 'btn--success'; ?>"
                                        onclick="return confirm('Are you sure?');">

                                        <?php if ($admin['status'] === 'active'): ?>

                                            <i class="fas fa-ban"></i>

                                            Suspend

                                        <?php else: ?>

                                            <i class="fas fa-check"></i>

                                            Activate

                                        <?php endif; ?>

                                    </a>

                                    <a
                                        href="index.php?route=toggle_role&id=<?= $admin['id']; ?>"
                                        class="btn btn--warning">

                                        <i class="fas fa-sync-alt"></i>

                                        Toggle Role

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