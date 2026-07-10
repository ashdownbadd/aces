<?php

$action = $action ?? '';
$route = $route ?? ($_GET['route'] ?? '');

$search = $search ?? '';
$placeholder = $placeholder ?? 'Search...';

$filters = $filters ?? [];
$actions = $actions ?? '';

?>

<form
    class="filter-bar"
    action="<?= htmlspecialchars($action) ?>"
    method="GET">

    <input
        type="hidden"
        name="route"
        value="<?= htmlspecialchars($route) ?>">

    <div class="filter-bar__left">

        <div class="filter-bar__search">

            <i class="fas fa-search filter-bar__icon"></i>

            <input
                type="search"
                name="search"
                class="filter-bar__input"
                placeholder="<?= htmlspecialchars($placeholder) ?>"
                value="<?= htmlspecialchars($search) ?>">

        </div>

        <?php foreach ($filters as $filter): ?>

            <select
                class="filter-bar__select"
                name="<?= htmlspecialchars($filter['name']) ?>"
                onchange="this.form.submit()">

                <?php foreach ($filter['options'] as $value => $label): ?>

                    <option
                        value="<?= htmlspecialchars($value) ?>"
                        <?= (string) $value === (string) ($filter['value'] ?? '') ? 'selected' : '' ?>>

                        <?= htmlspecialchars($label) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        <?php endforeach; ?>

    </div>

    <div class="filter-bar__right">

        <?= $actions ?>

    </div>

</form>