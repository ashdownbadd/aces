<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

    <?php

    c('page_header', [

        'title' => 'Activity Logs',

        'description' =>
        'Review administrator actions, security events, and system activity history.'

    ]);

    ?>

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

                    Audit Trail

                </h2>

                <p class="section__description">

                    Complete chronological history of system events.

                </p>

            </div>

        </div>

        <div class="section__body">

            <?php if (empty($logs)): ?>

                <div class="empty-state">

                    <div class="empty-state__icon">

                        <i class="fas fa-history"></i>

                    </div>

                    <h3>

                        No activity logs found

                    </h3>

                    <p>

                        No audit records are currently available.

                    </p>

                </div>

            <?php else: ?>

                <table class="table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>User</th>

                            <th>Action</th>

                            <th>Details</th>

                            <th>IP Address</th>

                            <th>Date & Time</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($logs as $log): ?>

                            <tr>

                                <td>

                                    <strong>

                                        #<?= (int) $log['id'] ?>

                                    </strong>

                                </td>

                                <td>

                                    <div class="loan-table__member">

                                        <strong>

                                            <?= htmlspecialchars(
                                                $log['username'] ?? 'System'
                                            ) ?>

                                        </strong>

                                        <small>

                                            UID:

                                            <?= htmlspecialchars(
                                                $log['user_id'] ?? 'N/A'
                                            ) ?>

                                        </small>

                                    </div>

                                </td>

                                <td>

                                    <span class="badge badge--secondary">

                                        <?= htmlspecialchars(
                                            $log['action']
                                        ) ?>

                                    </span>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $log['details']
                                    ) ?>

                                </td>

                                <td>

                                    <code>

                                        <?= htmlspecialchars(
                                            $log['ip_address'] ?? '0.0.0.0'
                                        ) ?>

                                    </code>

                                </td>

                                <td>

                                    <small>

                                        <?= htmlspecialchars(
                                            $log['created_at'] ?? 'Unknown'
                                        ) ?>

                                    </small>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </section>

</div>