<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$education = $member['education'] ?? [];

ob_start();

?>

<?php if (empty($education)): ?>

    <div class="member-empty">

        No educational records available.

    </div>

<?php else: ?>

    <?php foreach ($education as $record): ?>

        <div class="member-record">

            <div class="member-record__content">

                <h3 class="member-record__title">

                    <?= display_value($record['course'] ?? null, 'Unknown Course'); ?>

                </h3>

                <p class="member-record__subtitle">

                    <?= display_value($record['school'] ?? null, 'Unknown School'); ?>

                </p>

                <?php if (!empty($record['degree'])): ?>

                    <div class="member-record__meta">

                        <?= display_value($record['degree']); ?>

                    </div>

                <?php endif; ?>

            </div>

            <div class="member-record__value">

                <span class="member-record__label">

                    Graduated

                </span>

                <div class="member-record__amount">

                    <?= display_value(
                        $record['year_graduated'] ?? null,
                        'Present'
                    ); ?>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

<?php endif;

$body = ob_get_clean();

c('card', [

    'title' => 'Educational Background',

    'subtitle' => 'Academic qualifications and educational history.',

    'body' => $body

]);
