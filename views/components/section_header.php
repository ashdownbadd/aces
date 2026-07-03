<?php

$title ??= '';

$description ??= '';

?>

<div class="section__header">

    <div>

        <h2 class="section__title">

            <?= htmlspecialchars($title) ?>

        </h2>

        <?php if (!empty($description)): ?>

            <p class="section__description">

                <?= htmlspecialchars($description) ?>

            </p>

        <?php endif; ?>

    </div>

</div>