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

<div class="page member-profile-page">

    <div class="container container--lg">

        <?php

        c('page_header', [
            'title' => 'Member Profile',
            'description' => 'View financial information, activity, and supporting records for this member.'
        ]);

        ?>

        <?php

        c('member/hero', [
            'member' => $member
        ]);

        ?>

        <section class="member-workspace">

            <!-- ==========================================================
             Financial Health
        =========================================================== -->

            <section class="member-workspace__section">

                <header class="workspace-section">

                    <h2 class="workspace-section__title">
                        Financial Health
                    </h2>

                    <p class="workspace-section__description">
                        Monitor the member's current financial position.
                    </p>

                </header>

                <?php

                c('member/financial_summary', [
                    'member' => $member
                ]);

                ?>

            </section>

            <!-- ==========================================================
             Overview
        =========================================================== -->

            <section class="member-workspace__section">

                <header class="workspace-section">

                    <h2 class="workspace-section__title">
                        Overview
                    </h2>

                    <p class="workspace-section__description">
                        Recent activity and essential member information.
                    </p>

                </header>

                <div class="member-workspace__content">

                    <div class="member-workspace__main">

                        <?php

                        c('member/recent_transactions', [
                            'member' => $member
                        ]);

                        ?>

                    </div>

                    <aside class="member-workspace__sidebar">

                        <?php

                        c('member/member_information', [
                            'member' => $member
                        ]);

                        ?>

                    </aside>

                </div>

            </section>

            <!-- ==========================================================
             Supporting Records
        =========================================================== -->

            <section class="member-workspace__section">

                <header class="workspace-section">

                    <h2 class="workspace-section__title">
                        Supporting Records
                    </h2>

                    <p class="workspace-section__description">
                        Employment, educational background and beneficiary records.
                    </p>

                </header>

                <div class="member-workspace__records">

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

                    <?php

                    c('member/beneficiaries', [
                        'member' => $member
                    ]);

                    ?>

                </div>

            </section>

        </section>

    </div>

</div>