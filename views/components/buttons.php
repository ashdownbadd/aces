<?php

$href = $href ?? '#';
$text = $text ?? 'Button';
$icon = $icon ?? '';
$type = $type ?? 'primary';
$target = $target ?? '_self';

?>

<a
    href="<?= htmlspecialchars($href) ?>"
    target="<?= htmlspecialchars($target) ?>"
    class="btn btn--<?= htmlspecialchars($type) ?>">

    <?php if ($icon): ?>

        <i class="<?= htmlspecialchars($icon) ?>"></i>

    <?php endif; ?>

    <?= htmlspecialchars($text) ?>

</a>