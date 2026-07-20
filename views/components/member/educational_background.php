<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<section class="member-section">

    <h2 class="member-section__title">

        Educational Background

    </h2>

    <?php if (empty($member['education'])): ?>

        <p class="member-empty">

            No educational records found.

        </p>

    <?php else: ?>

        <div class="member-list">

            <?php foreach ($member['education'] as $education): ?>

                <div class="member-list__item">

                    <div>

                        <strong>

                            <?= display_value($education['course'] ?? null); ?>

                        </strong>

                        <br>

                        <span>

                            <?= display_value($education['school'] ?? null); ?>

                        </span>

                    </div>

                    <span>

                        <?= display_value($education['year_graduated'] ?? 'Present'); ?>

                    </span>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>