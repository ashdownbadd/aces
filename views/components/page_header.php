<?php

$title = $title ?? '';

$description = $description ?? '';

$actions = $actions ?? '';

?>

<header class="page-header">

    <div>

        <h1 class="page-header__title">

            <?= htmlspecialchars($title) ?>

        </h1>

        <?php if ($description): ?>

            <p class="page-header__description">

                <?= $description ?>

            </p>

        <?php endif; ?>

    </div>

    <?php if ($actions): ?>

        <div class="page-header__actions">

            <?= $actions ?>

        </div>

    <?php endif; ?>

</header>