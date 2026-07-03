<?php

c('page_header', [

    'title' => 'Generate Amortization Schedule',

    'description' =>
    'Create a new loan application and automatically generate its amortization schedule.'

]);

?>

<p class="page__back">

    <a href="<?= url('amortization_dashboard') ?>">

        ← Back to Loans Dashboard

    </a>

</p>

<?php c('flash_messages'); ?>