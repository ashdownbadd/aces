<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="member-onboarding__header">

    <div class="member-onboarding__back">

        <?php

        c('button', [

            'href' => url('members'),

            'text' => 'Back to Members',

            'icon' => 'fas fa-arrow-left',

            'type' => 'secondary'

        ]);

        ?>

    </div>

    <div class="member-onboarding__hero">

        <span class="member-onboarding__eyebrow">

            MEMBER REGISTRATION

        </span>

        <h1 class="member-onboarding__title">

            Create Member

        </h1>

        <p class="member-onboarding__description">

            Complete the steps below to register a new cooperative member.

        </p>

    </div>

</div>