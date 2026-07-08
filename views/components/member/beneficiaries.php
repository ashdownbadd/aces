<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<section class="member-section">

    <h2 class="member-section__title">

        Beneficiaries

    </h2>

    <?php if (empty($member['beneficiaries'])): ?>

        <p class="member-empty">

            No beneficiaries registered.

        </p>

    <?php else: ?>

        <div class="member-list">

            <?php foreach ($member['beneficiaries'] as $beneficiary): ?>

                <div class="member-list__item">

                    <strong>

                        <?= display_value(
                            trim(
                                ($beneficiary['first_name'] ?? '') . ' ' .
                                    ($beneficiary['last_name'] ?? '')
                            ),
                            'Unnamed Beneficiary'
                        ); ?>

                    </strong>

                    <span>

                        <?= display_value($beneficiary['relation'] ?? null); ?>

                    </span>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>