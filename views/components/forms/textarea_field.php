<?php

$name = $name ?? '';
$id = $id ?? $name;
$label = $label ?? '';
$value = $value ?? '';

?>

<div class="form-group">

    <?php if ($label): ?>

        <label
            for="<?= htmlspecialchars($id) ?>"
            class="form-label"
        >

            <?= htmlspecialchars($label) ?>

        </label>

    <?php endif; ?>

    <textarea

        id="<?= htmlspecialchars($id) ?>"

        name="<?= htmlspecialchars($name) ?>"

        class="form-control"

        rows="4"

    ><?= htmlspecialchars($value) ?></textarea>

</div>