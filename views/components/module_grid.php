<?php

$modules = $modules ?? [];

?>

<div class="module-grid">

    <?php foreach ($modules as $module): ?>

        <?php c('module_card', $module); ?>

    <?php endforeach; ?>

</div>