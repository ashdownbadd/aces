<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$employment = $member['employment'] ?? [];

?>

<section class="member-section">

    <h2 class="member-section__title">

        Employment Information

    </h2>

    <?php if (empty($employment)): ?>

        <p class="member-empty">

            No employment information found.

        </p>

    <?php else: ?>

        <div class="member-list">

            <div class="member-list__item">

                <div>

                    <strong>

                        <?= display_value($employment['occupation']); ?>

                    </strong>

                    <br>

                    <span>

                        <?= display_value($employment['employer_name']); ?>

                    </span>

                    <br>

                    <small>

                        <?= display_value($employment['employment_status']); ?>

                    </small>

                </div>

                <div class="member-list__meta">

                    ₱<?= number_format(
                            (float) ($employment['monthly_income'] ?? 0),
                            2
                        ); ?>

                </div>

            </div>

        </div>

    <?php endif; ?>

</section>