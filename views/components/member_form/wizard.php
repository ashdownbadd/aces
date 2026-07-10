<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$steps = [
    'personal',
    'contact',
    'address',
    'employment',
    'education',
    'beneficiaries',
    'review'
];

?>

<form
    id="memberWizard"
    class="member-onboarding"
    action="index.php?route=add_member"
    method="POST">

    <?php c('member_form/step_header'); ?>

    <?php c('member_form/step_progress'); ?>

    <section class="member-panel">

        <div class="member-panel__body">

            <?php foreach ($steps as $index => $step): ?>

                <section
                    class="member-step <?= $index === 0 ? 'is-active' : '' ?>">

                    <?php c("member_form/step_{$step}"); ?>

                </section>

            <?php endforeach; ?>

        </div>

        <footer class="member-panel__footer">

            <?php c('member_form/step_footer'); ?>

        </footer>

    </section>

</form>