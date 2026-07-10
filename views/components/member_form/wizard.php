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

    <div class="card member-onboarding__card">

        <div class="card__body">

            <?php foreach ($steps as $index => $step): ?>

                <section class="member-step <?= $index === 0 ? 'is-active' : '' ?>">

                    <?php c("member_form/step_{$step}"); ?>

                </section>

            <?php endforeach; ?>

        </div>

        <?php c('member_form/step_footer'); ?>

    </div>

</form>