<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$hour = (int) date('G');

if ($hour < 12) {
    $greeting = 'Good Morning';
} elseif ($hour < 18) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}

?>

<div class="page dashboard">

    <section class="dashboard-hero">

        <span class="dashboard-hero__eyebrow">
            <?= date('l, F j, Y'); ?>
        </span>

        <h1 class="dashboard-hero__title">
            <?= $greeting ?>,
            <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
        </h1>

        <p class="dashboard-hero__description">
            Welcome back to ACES. Here's what's happening today.
        </p>

    </section>

    <?php c('flash_messages'); ?>

    <?php c('stats_grid', [
        'cards' => $cards
    ]); ?>

    <section class="dashboard-grid">

        <div>

            <?php c('system_alerts', [
                'alerts' => $alerts
            ]); ?>

        </div>

        <aside class="dashboard-sidebar">

            <div class="card">

                <div class="card__header">
                    <h3 class="card__title">
                        Recent Activity
                    </h3>
                </div>

                <div class="card__body dashboard-placeholder">

                    <i class="fa-solid fa-clock-rotate-left dashboard-placeholder__icon"></i>

                    <h4>No Recent Activity</h4>

                    <p>
                        Recent member registrations, loans,
                        ledger entries and administrator
                        activity will appear here.
                    </p>

                </div>

            </div>

        </aside>

    </section>

</div>