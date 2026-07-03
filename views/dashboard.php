<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="container">



    <h1>Welcome to the Dashboard</h1>

    <p>
        This is the central command center for the Membership,
        Amortization, and General Ledger modules.
    </p>

    <?php if (isset($_SESSION['error_message'])): ?>

        <div class="alert alert--danger">

            <?= htmlspecialchars($_SESSION['error_message']); ?>

            <?php unset($_SESSION['error_message']); ?>

        </div>

    <?php endif; ?>

    <?php

    $alerts = (function_exists('getSystemAlerts') && isset($pdo))
        ? getSystemAlerts($pdo)
        : [
            'negative_equity' => [],
            'past_due_loans' => []
        ];

    ?>

    <?php if (!empty($alerts['negative_equity']) || !empty($alerts['past_due_loans'])): ?>

        <div class="alert alert--warning dashboard-alert">

            <h2 class="dashboard-alert__title">
                ⚠️ System Health Alerts
            </h2>

            <?php if (!empty($alerts['negative_equity'])): ?>

                <p>
                    <strong>Negative Share Capital:</strong>

                    <?= count($alerts['negative_equity']); ?>

                    member(s) have a balance below zero and require review.
                </p>

            <?php endif; ?>

            <?php if (!empty($alerts['past_due_loans'])): ?>

                <p>

                    <strong>Past Due Loans:</strong>

                    <?= count($alerts['past_due_loans']); ?>

                    active loan(s) are past their scheduled due date.

                </p>

                <a href="index.php?route=amortization_dashboard">
                    View Amortization Queue →
                </a>

            <?php endif; ?>

        </div>

    <?php endif; ?>


    <div class="dashboard-grid">

        <div class="dashboard-card">

            <h3 class="dashboard-card__title">
                Total Members
            </h3>

            <p class="dashboard-value">
                <?= $total_members ?? 0; ?>
            </p>

        </div>


        <div class="dashboard-card card--primary">

            <h3 class="dashboard-card__title">
                Types
            </h3>

            <p>
                Regular:
                <strong><?= $types['Regular'] ?? 0; ?></strong>
            </p>

            <p>
                Associate:
                <strong><?= $types['Associate'] ?? 0; ?></strong>
            </p>

        </div>


        <div class="dashboard-card card--success">

            <h3 class="dashboard-card__title">
                Status
            </h3>

            <p>
                Active:
                <strong><?= $status['active'] ?? 0; ?></strong>
            </p>

            <p>
                Inactive:
                <strong><?= $status['inactive'] ?? 0; ?></strong>
            </p>

        </div>


        <div class="dashboard-card card--info">

            <h3 class="dashboard-card__title">
                Gender
            </h3>

            <p>
                Male:
                <strong><?= $gender['Male'] ?? 0; ?></strong>
            </p>

            <p>
                Female:
                <strong><?= $gender['Female'] ?? 0; ?></strong>
            </p>

        </div>

    </div>

    <hr>

    <h3>Available Application Modules</h3>

    <ul class="module-list">

        <li class="module-list__item">

            <div class="card module-card">

                <a class="module-card__title"
                    href="index.php?route=members">

                    👥 Manage Cooperative Members Directory

                </a>

                <div class="module-card__description">

                    Track official cooperative shareholders,
                    initial capital logs, and statements.

                </div>

            </div>

        </li>

        <li class="module-list__item">

            <div class="card module-card">

                <a class="module-card__title"
                    href="index.php?route=amortization_dashboard">

                    📈 Amortization Calculators Module

                </a>

            </div>

        </li>

        <li class="module-list__item">

            <div class="card module-card">

                <a class="module-card__title"
                    href="index.php?route=ledger">

                    📖 General Ledger & Accounting Framework

                </a>

            </div>

        </li>

        <?php if (isset($_SESSION['role_id']) && intval($_SESSION['role_id']) === 1): ?>

            <li class="module-list__item">

                <div class="card card--warning module-card module-card--admin">

                    <a class="module-card__title"
                        href="index.php?route=admins">

                        <i class="fas fa-shield-halved"></i>

                        Manage System Operators & Staff Control Panel

                    </a>

                    <div class="module-card__description">

                        Administrative clearance node:
                        modify access rankings,
                        create credentials,
                        or trigger operator locks.

                    </div>

                    <hr>

                    <a class="module-card__title"
                        href="index.php?route=activity_logs">

                        📊 Review Global System Activity Logs

                    </a>

                </div>

            </li>

        <?php endif; ?>

    </ul>

</div>