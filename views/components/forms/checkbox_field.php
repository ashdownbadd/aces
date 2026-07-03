<?php

$name = $name ?? '';
$id = $id ?? $name;
$label = $label ?? '';
$checked = $checked ?? false;

?>

<label class="checkbox">

    <input

        type="checkbox"

        id="<?= htmlspecialchars($id) ?>"

        name="<?= htmlspecialchars($name) ?>"

        <?= $checked ? 'checked' : '' ?>

    >

    <?= htmlspecialchars($label) ?>

</label>