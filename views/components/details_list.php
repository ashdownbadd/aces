<?php

$items = $items ?? [];

?>

<?php if (!empty($items)): ?>

    <dl class="details-list">

        <?php foreach ($items as $label => $value): ?>

            <div class="details-list__item">

                <dt class="details-list__label">

                    <?= htmlspecialchars($label) ?>

                </dt>

                <dd class="details-list__value">

                    <?= $value ?>

                </dd>

            </div>

        <?php endforeach; ?>

    </dl>

<?php endif; ?>