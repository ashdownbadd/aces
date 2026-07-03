<?php

$name = $name ?? '';
$id = $id ?? $name;
$label = $label ?? '';
$options = $options ?? [];
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

    <select

        id="<?= htmlspecialchars($id) ?>"

        name="<?= htmlspecialchars($name) ?>"

        class="form-control"

    >

        <?php foreach ($options as $key => $text): ?>

            <option
                value="<?= htmlspecialchars($key) ?>"
                <?= $key == $value ? 'selected' : '' ?>
            >

                <?= htmlspecialchars($text) ?>

            </option>

        <?php endforeach; ?>

    </select>

</div>