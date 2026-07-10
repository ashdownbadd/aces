<?php

$type = $type ?? 'text';

$name = $name ?? '';
$id = $id ?? $name;

$label = $label ?? '';

$value = $value ?? '';
$placeholder = $placeholder ?? '';

$required = $required ?? false;
$readonly = $readonly ?? false;
$disabled = $disabled ?? false;

$autocomplete = $autocomplete ?? 'off';

$maxlength = $maxlength ?? null;
$minlength = $minlength ?? null;

$min = $min ?? null;
$max = $max ?? null;
$step = $step ?? null;

$pattern = $pattern ?? null;
$inputmode = $inputmode ?? null;

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

    <input
        class="form-control<?= $error ? ' is-invalid' : '' ?>"
        type="<?= htmlspecialchars($type) ?>"
        id="<?= htmlspecialchars($id) ?>"
        name="<?= htmlspecialchars($name) ?>"
        value="<?= htmlspecialchars($value) ?>"
        placeholder="<?= htmlspecialchars($placeholder) ?>"
        autocomplete="<?= htmlspecialchars($autocomplete) ?>"
        <?= $inputmode ? 'inputmode="' . htmlspecialchars($inputmode) . '"' : '' ?>
        <?= $pattern ? 'pattern="' . htmlspecialchars($pattern) . '"' : '' ?>
        <?= $maxlength !== null ? 'maxlength="' . (int) $maxlength . '"' : '' ?>
        <?= $minlength !== null ? 'minlength="' . (int) $minlength . '"' : '' ?>
        <?= $min !== null ? 'min="' . htmlspecialchars($min) . '"' : '' ?>
        <?= $max !== null ? 'max="' . htmlspecialchars($max) . '"' : '' ?>
        <?= $step !== null ? 'step="' . htmlspecialchars($step) . '"' : '' ?>
        <?= $required ? 'required' : '' ?>
        <?= $readonly ? 'readonly' : '' ?>
        <?= $disabled ? 'disabled' : '' ?>>

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