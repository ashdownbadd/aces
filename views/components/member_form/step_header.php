<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<header class="member-onboarding__header">

    <?php

    c('breadcrumb', [
        'items' => [
            [
                'label' => 'Members',
                'href' => url('members')
            ],
            [
                'label' => 'Create Member'
            ]
        ]
    ]);

    ?>

    <div class="member-onboarding__hero">

        <h1 class="member-onboarding__title">
            Create Member
        </h1>

        <p class="member-onboarding__description">
            Register a new cooperative member by completing the information below.
        </p>

    </div>

</header>