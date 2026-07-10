<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

ob_start();

if (isset($_SESSION['role_id']) && (int) $_SESSION['role_id'] === 1) {

    c('button', [
        'href' => url('add_member'),
        'text' => 'Register Member',
        'icon' => 'fas fa-user-plus',
        'type' => 'primary'
    ]);
} else {

    c('badge', [
        'type' => 'staff',
        'text' => 'Read Only'
    ]);
}

$actions = ob_get_clean();

?>

<div class="page member-page">

    <?php

    c('page_header', [
        'title' => 'Members',
        'description' => 'Manage cooperative members, registrations, and member records.'
    ]);

    ?>

    <?php c('flash_messages'); ?>

    <?php

    c('filter_bar', [
        'action' => 'index.php',
        'route' => 'members',

        'search' => $searchTerm ?? '',
        'placeholder' => 'Search members...',

        'filters' => [

            [
                'name' => 'status',
                'value' => $statusFilter ?? '',
                'options' => [
                    '' => 'All Status',
                    'active' => 'Active',
                    'inactive' => 'Inactive'
                ]
            ],

            [
                'name' => 'membership',
                'value' => $membershipFilter ?? '',
                'options' => [
                    '' => 'All Memberships',
                    'regular' => 'Regular',
                    'associate' => 'Associate'
                ]
            ]

        ],

        'actions' => $actions

    ]);

    ?>

    <div class="member-overview">

        <?php

        c('stat_card', [
            'title' => 'Total Members',
            'value' => number_format($totalMembers),
            'subtitle' => 'Registered Members',
            'icon' => 'fas fa-users',
            'color' => 'primary'
        ]);

        c('stat_card', [
            'title' => 'Active Members',
            'value' => number_format($activeMembers),
            'subtitle' => 'Currently Active',
            'icon' => 'fas fa-user-check',
            'color' => 'success'
        ]);

        c('stat_card', [
            'title' => 'Inactive Members',
            'value' => number_format($inactiveMembers),
            'subtitle' => 'Currently Inactive',
            'icon' => 'fas fa-user-times',
            'color' => 'warning'
        ]);

        c('stat_card', [
            'title' => 'Share Capital',
            'value' => '₱' . number_format($totalShareCapital, 2),
            'subtitle' => 'Total Share Capital',
            'icon' => 'fas fa-piggy-bank',
            'color' => 'info'
        ]);

        ?>

    </div>

    <?php

    c('table', [
        'caption' => 'Member Directory',
        'headers' => $headers,
        'rows' => $rows,
        'emptyMessage' => 'No cooperative members found.'
    ]);

    ?>

</div>