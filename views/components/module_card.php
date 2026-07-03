<?php

$title = $title ?? '';
$description = $description ?? '';
$icon = $icon ?? 'fas fa-cube';
$url = $url ?? '#';
$color = $color ?? 'primary';

?>

<a
    href="<?= htmlspecialchars($url) ?>"
    class="module-card module-card--<?= htmlspecialchars($color) ?>">

    <div class="module-card__icon">

        <i class="<?= htmlspecialchars($icon) ?>"></i>

    </div>

    <div class="module-card__content">

        <h3 class="module-card__title">

            <?= htmlspecialchars($title) ?>

        </h3>

        <?php if ($description): ?>

            <p class="module-card__description">

                <?= htmlspecialchars($description) ?>

            </p>

        <?php endif; ?>

    </div>

</a>