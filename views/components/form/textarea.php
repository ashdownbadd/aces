<?php

$name = $name ?? '';
$id = $id ?? $name;

$label = $label ?? '';

$value = $value ?? '';
$placeholder = $placeholder ?? '';

$rows = $rows ?? 4;

$required = $required ?? false;
$readonly = $readonly ?? false;
$disabled = $disabled ?? false;

$error = $error ?? '';
$help = $help ?? '';

?>

<div class="form-group">

    <?php if ($label): ?>

        <label
            class="form-label<?= $required ? ' form-label--required' : '' ?>"
            for="<?= htmlspecialchars($id) ?>">

            <?= htmlspecialchars($label) ?>

        </label>

    <?php endif; ?>

    <textarea
        class="form-control<?= $error ? ' is-invalid' : '' ?>"
        id="<?= htmlspecialchars($id) ?>"
        name="<?= htmlspecialchars($name) ?>"
        rows="<?= (int) $rows ?>"
        placeholder="<?= htmlspecialchars($placeholder) ?>"
        <?= $required ? 'required' : '' ?>
        <?= $readonly ? 'readonly' : '' ?>
        <?= $disabled ? 'disabled' : '' ?>><?= htmlspecialchars($value) ?></textarea>

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