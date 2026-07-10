<?php

$name = $name ?? '';
$id = $id ?? $name;

$label = $label ?? '';

$options = $options ?? [];
$value = $value ?? '';

$required = $required ?? false;
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

    <select
        class="form-control<?= $error ? ' is-invalid' : '' ?>"
        id="<?= htmlspecialchars($id) ?>"
        name="<?= htmlspecialchars($name) ?>"
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