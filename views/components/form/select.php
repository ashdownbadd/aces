<?php

$name = $name ?? '';
$id = $id ?? $name;

$label = $label ?? '';

$rules = $rules ?? [];

if (is_string($rules)) {
    $rules = [$rules];
}

$options = $options ?? [];
$value = $value ?? '';

$required = $required ?? false;
$disabled = $disabled ?? false;

if ($required && !in_array('required', $rules, true)) {
    $rules[] = 'required';
}

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

    <select
        class="form-control<?= $error ? ' is-invalid' : '' ?>"
        id="<?= htmlspecialchars($id) ?>"
        name="<?= htmlspecialchars($name) ?>"
        <?= !empty($rules)
            ? 'data-rules="' . htmlspecialchars(implode('|', $rules)) . '"'
            : '' ?>
        <?= $required ? 'required' : '' ?>
        <?= $disabled ? 'disabled' : '' ?>>

        <?php foreach ($options as $key => $text): ?>

            <option
                value="<?= htmlspecialchars($key) ?>"
                <?= (string) $key === (string) $value ? 'selected' : '' ?>>

                <?= htmlspecialchars($text) ?>

            </option>

        <?php endforeach; ?>

    </select>

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