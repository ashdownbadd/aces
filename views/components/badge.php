<?php

$type ??= 'primary';

$text ??= '';

?>

<span class="badge badge--<?= htmlspecialchars($type) ?>">

    <?= htmlspecialchars($text) ?>

</span>