<?php

$name = $name ?? '';
$id = $id ?? $name;

$label = $label ?? '';

$value = $value ?? '1';

$checked = $checked ?? false;

$disabled = $disabled ?? false;

$help = $help ?? '';

$error = $error ?? '';

?>

<div class="form-group">

    <label
        class="checkbox"
        for="<?= htmlspecialchars($id) ?>">

        <input
            type="checkbox"
            id="<?= htmlspecialchars($id) ?>"
            name="<?= htmlspecialchars($name) ?>"
            value="<?= htmlspecialchars($value) ?>"
            <?= $checked ? 'checked' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>>

        <span>

            <?= htmlspecialchars($label) ?>

        </span>

    </label>

    <?php if ($help): ?>

        <small class="form-help">

            <?= htmlspecialchars($help) ?>

        </small>

    <?php endif; ?>

    <?php if ($error): ?>

        <small class="form-error">

            <?= htmlspecialchars($error) ?>

        </small>

    <?php endif; ?>

</div>