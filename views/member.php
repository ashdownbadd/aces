<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

    <?php

    c('page_header', [

        'title' => 'Members',

        'description' => ''

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <div class="member-overview">

        <?php

        c('stat_card', [

            'title' => 'Total Members',

            'value' => number_format($totalMembers),

            'icon' => 'fas fa-users',

            'color' => 'primary'

        ]);

        c('stat_card', [

            'title' => 'Active Members',

            'value' => number_format($activeMembers),

            'icon' => 'fas fa-user-check',

            'color' => 'success'

        ]);

        c('stat_card', [

            'title' => 'Inactive Members',

            'value' => number_format($inactiveMembers),

            'icon' => 'fas fa-user-times',

            'color' => 'warning'

        ]);

        c('stat_card', [

            'title' => 'Share Capital',

            'value' => '₱' . number_format($totalShareCapital, 2),

            'icon' => 'fas fa-piggy-bank',

            'color' => 'secondary'

        ]);

        ?>

    </div>

    <?php

    /* ---------------------------------------------
     * Search
     * ------------------------------------------- */

    ob_start();

    c('search_box', [

        'action' => 'index.php',

        'value' => $searchTerm,

        'placeholder' => 'Search by member name or member number...'

    ]);

    $search = ob_get_clean();

    /* ---------------------------------------------
     * Actions
     * ------------------------------------------- */

    ob_start();

    if (isset($_SESSION['role_id']) && (int) $_SESSION['role_id'] === 1) {

        c('button', [

            'href' => url('add_member'),

            'text' => 'Register Member',

            'icon' => 'fas fa-user-plus',

            'type' => 'success'

        ]);
    } else {

        c('badge', [

            'type' => 'staff',

            'text' => 'Staff View (Read-Only)'

        ]);
    }

    $actions = ob_get_clean();

    c('toolbar', [

        'left' => $search,

        'right' => $actions

    ]);

    ?>

    <?php

    c('table', [

        'caption' => '',

        'headers' => $headers,

        'rows' => $rows,

        'emptyMessage' => 'No cooperative members found.'

    ]);

    ?>

</div>