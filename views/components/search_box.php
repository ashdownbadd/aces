<?php

$action = $action ?? '';

$value = $value ?? '';

$placeholder = $placeholder ?? 'Search...';

?>

<form
    action="<?= htmlspecialchars($action) ?>"
    method="GET"
    class="search-box"
>

    <input
        type="hidden"
        name="route"
        value="<?= htmlspecialchars($_GET['route'] ?? '') ?>"
    >

    <input

        class="search-box__input"

        type="text"

        name="search"

        value="<?= htmlspecialchars($value) ?>"

        placeholder="<?= htmlspecialchars($placeholder) ?>"

    >

    <button class="btn btn--primary">

        Search

    </button>

</form>