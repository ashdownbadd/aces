<?php

$action = $action ?? '';
$value = $value ?? '';
$placeholder = $placeholder ?? 'Search...';

?>

<form
    action="<?= htmlspecialchars($action) ?>"
    method="GET"
    class="search-box">

    <input
        type="hidden"
        name="route"
        value="<?= htmlspecialchars($_GET['route'] ?? '') ?>">

    <div class="search-box__field">

        <i class="fas fa-search search-box__icon"></i>

        <input

            class="search-box__input"

            type="search"

            name="search"

            value="<?= htmlspecialchars($value) ?>"

            placeholder="<?= htmlspecialchars($placeholder) ?>">

    </div>

</form>