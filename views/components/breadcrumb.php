<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$items = $items ?? [];

?>

<?php if ($items): ?>

    <nav class="breadcrumb" aria-label="Breadcrumb">

        <?php foreach ($items as $index => $item): ?>

            <?php if ($index > 0): ?>

                <span class="breadcrumb__separator">›</span>

            <?php endif; ?>

            <?php if (!empty($item['href']) && $index !== count($items) - 1): ?>

                <a
                    href="<?= htmlspecialchars($item['href']) ?>"
                    class="breadcrumb__link">

                    <?= htmlspecialchars($item['label']) ?>

                </a>

            <?php else: ?>

                <span class="breadcrumb__current">

                    <?= htmlspecialchars($item['label']) ?>

                </span>

            <?php endif; ?>

        <?php endforeach; ?>

    </nav>

<?php endif; ?>