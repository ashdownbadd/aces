<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<section class="member-section">

    <h2 class="member-section__title">

        Employment History

    </h2>

    <?php if (empty($member['employment'])): ?>

        <p class="member-empty">

            No employment records found.

        </p>

    <?php else: ?>

        <div class="member-list">

            <?php foreach ($member['employment'] as $employment): ?>

                <div class="member-list__item">

                    <div>

                        <strong>

                            <?= display_value($employment['company_name'] ?? null); ?>

                        </strong>

                        <br>

                        <span>

                            <?= display_value($employment['position'] ?? null); ?>

                        </span>

                    </div>

                    <div class="member-list__meta">

                        <span>

                            <?= display_value($employment['date_started'] ?? null); ?>

                            —

                            <?= display_value($employment['date_ended'] ?? 'Present'); ?>

                        </span>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>