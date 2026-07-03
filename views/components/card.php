<?php

$title = $title ?? '';
$body = $body ?? '';
$footer = $footer ?? '';

?>

<div class="card">

    <?php if ($title): ?>

        <div class="card__header">

            <h3 class="card__title">

                <?= htmlspecialchars($title) ?>

            </h3>

        </div>

    <?php endif; ?>

    <div class="card__body">

        <?= $body ?>

    </div>

    <?php if ($footer): ?>

        <div class="card__footer">

            <?= $footer ?>

        </div>

    <?php endif; ?>

</div>