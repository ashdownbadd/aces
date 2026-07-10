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

    <div class="stat-card__header">

        <div class="stat-card__heading">

            <div class="stat-card__title">

                <?= htmlspecialchars($title) ?>

            </div>

        </div>

        <?php if ($icon): ?>

            <div class="stat-card__icon">

                <i class="<?= htmlspecialchars($icon) ?>"></i>

            </div>

        <?php endif; ?>

    </div>

    <div class="stat-card__body">

        <div class="stat-card__value">

            <?= htmlspecialchars((string) $value) ?>

        </div>

        <?php if ($subtitle): ?>

            <div class="stat-card__subtitle">

                <?= htmlspecialchars($subtitle) ?>

            </div>

        <?php endif; ?>

    </div>

    <?php if ($footer): ?>

        <div class="stat-card__footer">

            <?= $footer ?>

        </div>

    <?php endif; ?>

</<?= $tag ?>>