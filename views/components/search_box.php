<?php

$action = $action ?? '';
$value = $value ?? '';
$placeholder = $placeholder ?? 'Search...';

?>

<form
    class="form-search"
    action="<?= htmlspecialchars($action) ?>"
    method="GET">

    <input
        type="hidden"
        name="route"
        value="<?= htmlspecialchars($_GET['route'] ?? '') ?>">

    <div class="form-search__field">

        <i class="fas fa-search form-search__icon"></i>

        <input
            class="form-search__input"
            type="search"
            name="search"
            value="<?= htmlspecialchars($value) ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            autocomplete="off">

    </div>

</form>