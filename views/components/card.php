<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$title = $title ?? '';
$subtitle = $subtitle ?? '';
$body = $body ?? '';
$footer = $footer ?? '';

$class = trim($class ?? '');

$classes = trim('card ' . $class);

?>

<div class="<?= htmlspecialchars($classes); ?>">

    <?php if ($title || $subtitle): ?>

        <div class="card__header">

            <div class="card__heading">

                <?php if ($title): ?>

                    <h3 class="card__title">

                        <?= htmlspecialchars($title); ?>

                    </h3>

                <?php endif; ?>

                <?php if ($subtitle): ?>

                    <p class="card__subtitle">

                        <?= htmlspecialchars($subtitle); ?>

                    </p>

                <?php endif; ?>

            </div>

        </div>

    <?php endif; ?>

    <div class="card__body">

        <?= $body; ?>

    </div>

    <?php if ($footer): ?>

        <div class="card__footer">

            <?= $footer; ?>

        </div>

    <?php endif; ?>

</div>