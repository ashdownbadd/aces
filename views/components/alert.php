<?php

$type = $type ?? 'info';

$title = $title ?? '';

$message = $message ?? '';

?>

<div class="alert alert--<?= htmlspecialchars($type) ?>">

    <?php if ($title): ?>

        <div class="alert__title">

            <?= htmlspecialchars($title) ?>

        </div>

    <?php endif; ?>

    <div class="alert__body">

        <?= $message ?>

    </div>

</div>