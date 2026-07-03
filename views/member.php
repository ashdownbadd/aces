<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

    <?php

    c('page_header', [

        'title' => 'Cooperative Members',

        'description' =>
        'Manage cooperative members, registrations, and shareholder information.'

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <?php

    ob_start();

    if (isset($_SESSION['role_id']) && (int)$_SESSION['role_id'] === 1) {

        c('button', [

            'href' => url('add_member'),

            'text' => 'Register New Member',

            'icon' => 'fas fa-user-plus',

            'type' => 'success'

        ]);
    } else {

        c('badge', [

            'type' => 'staff',

            'text' => 'Staff View (Read-Only)'

        ]);
    }

    $toolbarLeft = ob_get_clean();

    ob_start();

    c('search_box', [

        'action' => 'index.php',

        'value' => $searchTerm,

        'placeholder' => 'Search by member name or member number...'

    ]);

    $toolbarRight = ob_get_clean();

    c('toolbar', [

        'left' => $toolbarLeft,

        'right' => $toolbarRight

    ]);

    ?>

    <?php

    c('section_header', [

        'title' => 'Member Registry',

        'description' => 'List of registered cooperative members.'

    ]);

    ?>

    <?php

    c('table', [

        'headers' => $headers,

        'rows' => $rows,

        'emptyMessage' => 'No cooperative members found.'

    ]);

    ?>

</div>