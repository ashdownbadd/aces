<?php

$title ??= '';
$description ??= '';
$actions ??= '';

?>

<div class="page__header">

    <div class="page__heading">

        <h1 class="page__title">

            <?= htmlspecialchars($title) ?>

        </h1>

        <?php if (!empty($description)): ?>

            <p class="page__description">

                <?= $description ?>

            </p>

        <?php endif; ?>

    </div>

    <?php if (!empty($actions)): ?>

        <div class="page__actions">

            <?= $actions ?>

        </div>

    <?php endif; ?>

</div>