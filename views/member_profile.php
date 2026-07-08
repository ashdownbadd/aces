<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

if (!function_exists('display_value')) {
    function display_value($value, $fallback = 'N/A')
    {
        return htmlspecialchars(!empty($value) ? $value : $fallback);
    }
}

?>

<div class="container container--lg">

    <div class="member-toolbar">

        <?php

        c('button', [

            'href' => url('members'),

            'text' => 'Members',

            'icon' => 'fas fa-arrow-left',

            'type' => 'secondary'

        ]);

        ?>

        <?php

        c('button', [

            'href' => 'index.php?route=member_edit&id=' . ($member['id'] ?? ''),

            'text' => 'Edit Member',

            'icon' => 'fas fa-pen',

            'type' => 'primary'

        ]);

        ?>

    </div>

    <?php

    c('member/hero', [

        'member' => $member

    ]);

    ?>

    <?php

    c('member/financial_summary', [

        'member' => $member

    ]);

    ?>

    <section class="member-sections">

        <?php

        c('member/personal_information', [

            'member' => $member

        ]);

        ?>

        <?php

        c('member/contact_information', [

            'member' => $member

        ]);

        ?>

        <?php

        c('member/beneficiaries', [

            'member' => $member

        ]);

        ?>

        <?php

        c('member/employment_history', [

            'member' => $member

        ]);

        ?>

        <?php

        c('member/educational_background', [

            'member' => $member

        ]);

        ?>

    </section>

</div>