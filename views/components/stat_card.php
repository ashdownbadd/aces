<?php

$modifier ??= 'primary';

$label ??= '';

$value ??= '';

$description ??= '';

$icon ??= '';

?>

<div class="stats__card stats__card--<?= htmlspecialchars($modifier) ?>">

    <div class="stats__content">

        <span class="stats__label">

            <?= htmlspecialchars($label) ?>

        </span>

        <span class="stats__value">

            <?= htmlspecialchars((string) $value) ?>

        </span>

        <?php if (!empty($description)): ?>

            <span class="stats__description">

                <?= $description ?>

            </span>

        <?php endif; ?>

    </div>

    <div class="stats__icon">

        <i class="<?= htmlspecialchars($icon) ?>"></i>

    </div>

</div>