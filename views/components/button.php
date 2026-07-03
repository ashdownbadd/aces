<?php

$href ??= '#';

$text ??= '';

$type ??= 'primary';

$icon ??= '';

?>

<a
    href="<?= htmlspecialchars($href) ?>"
    class="btn btn--<?= htmlspecialchars($type) ?>">

    <?php if (!empty($icon)): ?>

        <i class="<?= htmlspecialchars($icon) ?>"></i>

    <?php endif; ?>

    <?= htmlspecialchars($text) ?>

</a>