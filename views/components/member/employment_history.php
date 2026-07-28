<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$employment = $member['employment'] ?? [];

ob_start();

?>

<?php if (empty($employment)): ?>

    <div class="member-empty">

        No employment information available.

    </div>

<?php else: ?>

    <div class="member-record">

        <div class="member-record__content">

            <h3 class="member-record__title">

                <?= display_value($employment['occupation']); ?>

            </h3>

            <p class="member-record__subtitle">

                <?= display_value($employment['employer_name']); ?>

            </p>

            <div class="member-record__meta">

                <?= display_value($employment['employment_status']); ?>

            </div>

        </div>

        <div class="member-record__value">

            <span class="member-record__label">

                Monthly Income

            </span>

            <div class="member-record__amount">

                $<?= number_format(
                        (float) ($employment['monthly_income'] ?? 0),
                        2
                    ); ?>

            </div>

        </div>

    </div>

<?php endif;

$body = ob_get_clean();

c('card', [

    'title' => 'Employment',

    'subtitle' => 'Current employment information.',

    'body' => $body

]);
