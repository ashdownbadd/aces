<?php

$title = $title ?? 'No Data';

$message = $message ?? '';

?>

<div class="empty-state">

    <i class="fas fa-folder-open empty-state__icon"></i>

    <h3>

        <?= htmlspecialchars($title) ?>

    </h3>

    <p>

        <?= htmlspecialchars($message) ?>

    </p>

</div>