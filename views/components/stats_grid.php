<?php

$cards = $cards ?? [];

?>

<div class="stats-grid">

    <?php foreach ($cards as $card): ?>

        <?php c('stat_card', $card); ?>

    <?php endforeach; ?>

</div>