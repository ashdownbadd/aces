<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$beneficiaries = $member['beneficiaries'] ?? [];

ob_start();

?>

<?php if (empty($beneficiaries)): ?>

    <div class="member-empty">

        No beneficiaries registered.

    </div>

<?php else: ?>

    <div class="member-list">

        <?php foreach ($beneficiaries as $beneficiary): ?>

            <article class="member-list__item">

                <div class="member-list__avatar">

                    <?= strtoupper(substr(
                        display_value($beneficiary['full_name'], 'U'),
                        0,
                        1
                    )); ?>

                </div>

                <div class="member-list__content">

                    <h4 class="member-list__title">

                        <?= display_value(
                            $beneficiary['full_name'],
                            'Unnamed Beneficiary'
                        ); ?>

                    </h4>

                    <span class="badge badge--secondary">

                        <?= display_value(
                            $beneficiary['relationship'],
                            'Relationship Not Specified'
                        ); ?>

                    </span>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif;

$body = ob_get_clean();

c('card', [

    'title' => 'Beneficiaries',

    'subtitle' => 'Registered beneficiary records.',

    'body' => $body

]);
