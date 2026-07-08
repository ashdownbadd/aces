<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

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

            <section class="member-step is-active">

                <?php c('member_form/step_personal'); ?>

            </section>

            <section class="member-step">

                <?php c('member_form/step_contact'); ?>

            </section>

            <section class="member-step">

                <?php c('member_form/step_address'); ?>

            </section>

            <section class="member-step">

                <?php c('member_form/step_review'); ?>

            </section>

        </div>

        <?php c('member_form/step_footer'); ?>

    </div>

</form>