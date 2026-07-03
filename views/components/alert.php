<?php

$type ??= 'info';
$title ??= '';
$message ??= '';

?>

<div class="alert alert--<?= htmlspecialchars($type) ?>">

    <?php if (!empty($title)): ?>

        <div class="alert__title">

            <?= $title ?>

        </div>

    <?php endif; ?>

    <div class="alert__body">

        <?= $message ?>

    </div>

</div>