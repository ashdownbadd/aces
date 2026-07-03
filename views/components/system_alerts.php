<?php

$alerts ??= [
    'negative_equity' => [],
    'past_due_loans'  => []
];

$hasAlerts =
    !empty($alerts['negative_equity']) ||
    !empty($alerts['past_due_loans']);

if ($hasAlerts) {

    ob_start();

    if (!empty($alerts['negative_equity'])) {
?>
        <p>
            <strong><?= count($alerts['negative_equity']) ?></strong>
            member(s) have a negative share capital balance.
        </p>
    <?php
    }

    if (!empty($alerts['past_due_loans'])) {
    ?>
        <p>
            <strong><?= count($alerts['past_due_loans']) ?></strong>
            loan account(s) are already overdue.
        </p>

<?php
        c('button', [
            'href' => url('amortization_dashboard'),
            'text' => 'View Loan Dashboard',
            'type' => 'warning',
            'icon' => 'fas fa-arrow-right'
        ]);
    }

    $body = ob_get_clean();

    c('alert', [
        'type'    => 'warning',
        'title'   => 'System Health Alerts',
        'message' => $body
    ]);
} else {

    c('alert', [
        'type'    => 'success',
        'title'   => 'System Healthy',
        'message' => 'No negative share capital or overdue loans were detected.'
    ]);
}
