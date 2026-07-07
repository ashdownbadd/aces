<?php

$title = $title ?? '';
$value = $value ?? '';
$subtitle = $subtitle ?? '';
$icon = $icon ?? '';
$color = $color ?? 'primary';
$url = $url ?? '';
$footer = $footer ?? '';

$tag = $url ? 'a' : 'div';

?>

<<?= $tag ?>
    <?= $url ? 'href="' . htmlspecialchars($url) . '"' : '' ?>
    class="stat-card stat-card--<?= htmlspecialchars($color) ?>">

    <div class="stat-card__body">

        <?php if ($icon): ?>

            <div class="stat-card__icon">

                <i class="<?= htmlspecialchars($icon) ?>"></i>

            </div>

        <?php endif; ?>

        <div class="stat-card__content">

            <span class="stat-card__title">

                <?= htmlspecialchars($title) ?>

            </span>

            <div class="stat-card__value">

                <?= htmlspecialchars((string) $value) ?>

            </div>

            <?php if ($subtitle): ?>

                <div class="stat-card__subtitle">

                    <?= htmlspecialchars($subtitle) ?>

                </div>

            <?php endif; ?>

        </div>

    </div>

    <?php if ($footer): ?>

        <div class="stat-card__footer">

            <?= $footer ?>

        </div>

    <?php endif; ?>

</<?= $tag ?>>