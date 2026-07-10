<?php

$href = $href ?? null;
$text = $text ?? '';

$type = $type ?? 'primary';
$size = $size ?? 'md';

$icon = $icon ?? '';
$block = $block ?? false;

$classes = [
    'btn',
    'btn--' . $type,
    'btn--' . $size
];

if ($block) {
    $classes[] = 'btn--block';
}

$class = implode(' ', $classes);

?>

<?php if ($href): ?>

    <a
        href="<?= htmlspecialchars($href) ?>"
        class="<?= htmlspecialchars($class) ?>">

        <?php if ($icon): ?>

            <i class="<?= htmlspecialchars($icon) ?>"></i>

        <?php endif; ?>

        <span><?= htmlspecialchars($text) ?></span>

    </a>

<?php else: ?>

    <button
        type="submit"
        class="<?= htmlspecialchars($class) ?>">

        <?php if ($icon): ?>

            <i class="<?= htmlspecialchars($icon) ?>"></i>

        <?php endif; ?>

        <span><?= htmlspecialchars($text) ?></span>

    </button>

<?php endif; ?>