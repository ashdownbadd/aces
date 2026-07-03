<?php

$type = $type ?? 'primary';
$text = $text ?? '';

?>

<span class="badge badge--<?= htmlspecialchars($type) ?>">

    <?= htmlspecialchars($text) ?>

</span>