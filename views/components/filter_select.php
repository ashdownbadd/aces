<?php

$name = $name ?? '';
$value = $value ?? '';
$options = $options ?? [];

?>

<div class="form-filter">

    <select
        class="form-filter__select"
        name="<?= htmlspecialchars($name) ?>">

        <?php foreach ($options as $optionValue => $label): ?>

            <option
                value="<?= htmlspecialchars($optionValue) ?>"
                <?= (string) $optionValue === (string) $value ? 'selected' : '' ?>>

                <?= htmlspecialchars($label) ?>

            </option>

        <?php endforeach; ?>

    </select>

</div>