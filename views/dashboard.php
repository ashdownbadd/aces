<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

// The controller should provide $alerts.
// Fallback only to prevent errors during development.
$alerts ??= [
    'negative_equity' => [],
    'past_due_loans'  => []
];

$hasAlerts =
    !empty($alerts['negative_equity']) ||
    !empty($alerts['past_due_loans']);

?>

<div class="page">

    <!-- ========================================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================================= -->

    <div class="page__header">

        <div class="page__heading">

            <h1 class="page__title">
                Dashboard
            </h1>

            <p class="page__description">
                Welcome back,
                <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.
                Here's a quick overview of your cooperative management system.
            </p>

        </div>

    </div>

    <!-- ========================================================= -->
    <!-- FLASH MESSAGES -->
    <!-- ========================================================= -->

    <?php if (!empty($_SESSION['success_message'])): ?>

        <div class="alert alert--success">

            <strong>Success</strong>

            <p>

                <?= htmlspecialchars($_SESSION['success_message']) ?>

            </p>

        </div>

        <?php unset($_SESSION['success_message']); ?>

    <?php endif; ?>

    <?php if (!empty($_SESSION['error_message'])): ?>

        <div class="alert alert--danger">

            <strong>Error</strong>

            <p>

                <?= htmlspecialchars($_SESSION['error_message']) ?>

            </p>

        </div>

        <?php unset($_SESSION['error_message']); ?>

    <?php endif; ?>

    <!-- ========================================================= -->
    <!-- SYSTEM OVERVIEW -->
    <!-- ========================================================= -->

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    System Overview

                </h2>

                <p class="section__description">

                    Key cooperative statistics.

                </p>

            </div>

        </div>

        <div class="section__body">

            <div class="stats">

                <div class="stats__card stats__card--primary">

                    <div class="stats__content">

                        <span class="stats__label">

                            Members

                        </span>

                        <span class="stats__value">

                            <?= $total_members ?? 0 ?>

                        </span>

                        <span class="stats__description">

                            Registered cooperative members

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-users"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--gold">

                    <div class="stats__content">

                        <span class="stats__label">

                            Regular Members

                        </span>

                        <span class="stats__value">

                            <?= $types['Regular'] ?? 0 ?>

                        </span>

                        <span class="stats__description">

                            Associate:
                            <?= $types['Associate'] ?? 0 ?>

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-id-card"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--success">

                    <div class="stats__content">

                        <span class="stats__label">

                            Active Members

                        </span>

                        <span class="stats__value">

                            <?= $status['active'] ?? 0 ?>

                        </span>

                        <span class="stats__description">

                            Inactive:
                            <?= $status['inactive'] ?? 0 ?>

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-user-check"></i>

                    </div>

                </div>

                <div class="stats__card stats__card--warning">

                    <div class="stats__content">

                        <span class="stats__label">

                            Female Members

                        </span>

                        <span class="stats__value">

                            <?= $gender['Female'] ?? 0 ?>

                        </span>

                        <span class="stats__description">

                            Male:
                            <?= $gender['Male'] ?? 0 ?>

                        </span>

                    </div>

                    <div class="stats__icon">

                        <i class="fas fa-venus"></i>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- ========================================================= -->
    <!-- SYSTEM HEALTH -->
    <!-- ========================================================= -->

    <section class="section">

        <div class="section__header">

            <div>

                <h2 class="section__title">

                    System Health

                </h2>

                <p class="section__description">

                    Monitor issues that require administrator attention.

                </p>

            </div>

        </div>

        <div class="section__body">

            <?php if ($hasAlerts): ?>

                <div class="alert alert--warning">

                    <div class="alert__title">

                        <i class="fas fa-triangle-exclamation"></i>

                        System Health Alerts

                    </div>

                    <?php if (!empty($alerts['negative_equity'])): ?>

                        <p>

                            <strong><?= count($alerts['negative_equity']) ?></strong>

                            member(s) currently have a negative share capital balance.

                        </p>

                    <?php endif; ?>

                    <?php if (!empty($alerts['past_due_loans'])): ?>

                        <p>

                            <strong><?= count($alerts['past_due_loans']) ?></strong>

                            loan account(s) are already past due.

                        </p>

                        <a
                            href="index.php?route=amortization_dashboard"
                            class="btn btn--warning">

                            View Loan Dashboard

                        </a>

                    <?php endif; ?>

                </div>

            <?php else: ?>

                <div class="alert alert--success">

                    <div class="alert__title">

                        <i class="fas fa-circle-check"></i>

                        System Healthy

                    </div>

                    <p>

                        No negative share capital or overdue loans were detected.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

</div>