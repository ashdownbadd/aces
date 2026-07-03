<?php

$title       = $title ?? '';
$value       = $value ?? '';
$subtitle    = $subtitle ?? '';
$icon        = $icon ?? '';
$color       = $color ?? 'primary';
$url         = $url ?? '';
$footer      = $footer ?? '';
$description = $description ?? '';

?>

<?php if ($url): ?>

    <a
        href="<?= htmlspecialchars($url) ?>"
        class="stat-card stat-card--<?= htmlspecialchars($color) ?>">

    <?php else: ?>

        <div
            class="stat-card stat-card--<?= htmlspecialchars($color) ?>">

        <?php endif; ?>

        <div class="stat-card__body">

            <div class="stat-card__content">

                <span class="stat-card__title">

                    <?= htmlspecialchars($title) ?>

                </span>

                <h2 class="stat-card__value">

                    <?= htmlspecialchars((string)$value) ?>

                </h2>

                <?php if ($subtitle): ?>

                    <span class="stat-card__subtitle">

                        <?= htmlspecialchars($subtitle) ?>

                    </span>

                <?php endif; ?>

                <?php if ($description): ?>

                    <small class="stat-card__description">

                        <?= htmlspecialchars($description) ?>

                    </small>

                <?php endif; ?>

            </div>

            <?php if ($icon): ?>

                <div class="stat-card__icon">

                    <i class="<?= htmlspecialchars($icon) ?>"></i>

                </div>

            <?php endif; ?>

        </div>

        <?php if ($footer): ?>

            <div class="stat-card__footer">

                <?= $footer ?>

            </div>

        <?php endif; ?>

        <?php if ($url): ?>

    </a>

<?php else: ?>

    </div>

<?php endif; ?>