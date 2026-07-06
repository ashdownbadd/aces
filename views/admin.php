<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$admins = $members ?? [];

?>

<div class="page">

    <?php

    c('page_header', [

        'title' => 'System Administration',

        'description' =>
        'Manage administrator accounts, user roles, and system access.'

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <div class="page__actions">

        <a
            href="<?= url('dashboard') ?>"
            class="btn btn--secondary">

            <i class="fas fa-arrow-left"></i>

            Back to Dashboard

        </a>

    </div>

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    Administrator Accounts

                </h2>

                <p class="section__description">

                    Review administrator privileges, account status, and role assignments.

                </p>

            </div>

        </div>

        <div class="section__body">

            <table class="table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Username</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($admins)): ?>

                        <tr>

                            <td colspan="6" class="table__empty">

                                No administrator accounts found.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($admins as $admin): ?>

                            <tr>

                                <td>

                                    <code>

                                        #<?= (int) $admin['id'] ?>

                                    </code>

                                </td>

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $admin['username']
                                        ) ?>

                                    </strong>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $admin['email']
                                    ) ?>

                                </td>

                                <td>

                                    <?php

                                    $isAdmin =
                                        (int) $admin['role_id'] === 1
                                        || $admin['role_name'] === 'Admin';

                                    ?>

                                    <span
                                        class="badge <?= $isAdmin
                                                            ? 'badge--danger'
                                                            : 'badge--secondary' ?>">

                                        <?= $isAdmin
                                            ? 'Administrator'
                                            : 'Staff' ?>

                                    </span>

                                </td>

                                <td>

                                    <?php

                                    $isActive =
                                        $admin['status'] === 'active';

                                    ?>

                                    <span
                                        class="status <?= $isActive
                                                            ? 'status--active'
                                                            : 'status--inactive' ?>">

                                        <?= $isActive
                                            ? '● Active'
                                            : '● Inactive' ?>

                                    </span>

                                </td>

                                <td>

                                    <?php if ($admin['id'] == $_SESSION['user_id']): ?>

                                        <span class="status status--self">

                                            Current Session

                                        </span>

                                    <?php else: ?>

                                        <div class="action-group">

                                            <a
                                                href="<?= url(
                                                            'toggle_status&id='
                                                                . (int) $admin['id']
                                                        ) ?>"
                                                class="btn <?= $isActive
                                                                ? 'btn--danger'
                                                                : 'btn--success' ?>"
                                                onclick="return confirm('Are you sure you want to <?= $isActive ? 'suspend' : 'activate' ?> this account?');">

                                                <?php if ($isActive): ?>

                                                    <i class="fas fa-ban"></i>

                                                    Suspend

                                                <?php else: ?>

                                                    <i class="fas fa-check"></i>

                                                    Activate

                                                <?php endif; ?>

                                            </a>

                                            <a
                                                href="<?= url(
                                                            'toggle_role&id='
                                                                . (int) $admin['id']
                                                        ) ?>"
                                                class="btn btn--warning"
                                                onclick="return confirm('Toggle this user's role?');">

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

    </section>

</div>