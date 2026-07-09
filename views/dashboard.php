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
        <div>
            <span class="dashboard-hero__eyebrow">
                <?= date('l, F j, Y'); ?>
            </span>
            <h1 class="dashboard-hero__title">
                <?= $greeting ?>,
                <strong><?= htmlspecialchars($_SESSION['username']); ?></strong> 👋
            </h1>
            <p class="dashboard-hero__description">
                Welcome back to ACES. Here's what's happening today.
            </p>
        </div>
    </section>
    <?php c('flash_messages'); ?>
    <?php c('stats_grid', [
        'cards' => $cards
    ]); ?>
    <?php c('system_alerts', [
        'alerts' => $alerts
    ]); ?>
</div>