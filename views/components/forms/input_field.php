<?php

$type        = $type ?? 'text';
$name        = $name ?? '';
$id          = $id ?? $name;
$label       = $label ?? '';
$value       = $value ?? '';
$placeholder = $placeholder ?? '';
$required    = $required ?? false;
$readonly    = $readonly ?? false;
$disabled    = $disabled ?? false;
$autocomplete = $autocomplete ?? 'off';
$error       = $error ?? '';
$help        = $help ?? '';

?>

<div class="form-group">

    <?php if ($label): ?>

        <label
            class="form-label"
            for="<?= htmlspecialchars($id) ?>">

            <?= htmlspecialchars($label) ?>

            <?php if ($required): ?>

                <span class="text-danger">*</span>

            <?php endif; ?>

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