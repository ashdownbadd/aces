<?php

$title = $title ?? '';

$description = $description ?? '';

?>

<div class="section-header">

    <h2 class="section-header__title">

        <?= htmlspecialchars($title) ?>

    </h2>

    <?php if ($description): ?>

        <p class="section-header__description">

            <?= htmlspecialchars($description) ?>

        </p>

    <?php endif; ?>

</div>