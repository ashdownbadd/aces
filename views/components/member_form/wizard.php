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

$isEdit = !empty($member);

$formAction = $isEdit
    ? 'index.php?route=member_update&id=' . (int) $member['id']
    : 'index.php?route=add_member';

?>

<form
    id="memberWizard"
    class="member-onboarding"
    action="<?= $formAction ?>"
    method="POST">

    <input
        type="hidden"
        id="beneficiariesJson"
        name="beneficiaries">

    <input
        type="hidden"
        id="existingBeneficiaries"
        value="<?= htmlspecialchars(
                    json_encode($member['beneficiaries'] ?? []),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>">

    <?php c('member_form/step_header'); ?>

    <?php c('member_form/step_progress'); ?>

    <section class="member-panel">

        <div class="member-panel__body">

            <?php foreach ($steps as $index => $step): ?>

                <section
                    class="member-step <?= $index === 0 ? 'is-active' : '' ?>">

                    <?php c(
                        "member_form/step_{$step}",
                        [
                            'member' => $member ?? []
                        ]
                    ); ?>

                </section>

            <?php endforeach; ?>

        </div>

        <footer class="member-panel__footer">

            <?php c('member_form/step_footer'); ?>

        </footer>

    </section>

</form>