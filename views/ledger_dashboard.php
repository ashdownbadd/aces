<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

    <?php

    c('page_header', [

        'title' => 'General Ledger',

        'description' => 'Track journal vouchers, share capital, dividends, and member equity.'

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <?php

    c('stats_grid', [

        'cards' => $kpis

    ]);

    ?>

    <?php

    ob_start();

    c('search_box', [

        'route' => 'ledger',

        'action' => 'index.php',

        'value' => $search,

        'placeholder' => 'Search member...'

    ]);

    ?>

    <input
        type="date"
        name="start_date"
        value="<?= htmlspecialchars($start_date) ?>">

    <input
        type="date"
        name="end_date"
        value="<?= htmlspecialchars($end_date) ?>">

    <button class="btn btn--primary">

        Filter

    </button>

    <?php

    $right = ob_get_clean();

    ob_start();

    c('button', [

        'href' => url('add_ledger_entry'),

        'text' => 'New Voucher',

        'type' => 'primary',

        'icon' => 'fas fa-plus'

    ]);

    echo " ";

    c('button', [

        'href' => url('pending_approvals'),

        'text' => 'Pending',

        'type' => 'warning',

        'icon' => 'fas fa-clock'

    ]);

    $left = ob_get_clean();

    c('toolbar', [

        'left' => $left,

        'right' => $right

    ]);

    ?>

    <?php

    c('section_header', [

        'title' => 'Member Share Capital'

    ]);

    ?>

    <?php

    c(
        'table',

        $memberTable

    );

    ?>

    <?php

    c('section_header', [

        'title' => 'Recent Journal Vouchers'

    ]);

    ?>

    <?php

    c(
        'table',

        $voucherTable

    );

    ?>

</div>