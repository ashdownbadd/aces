<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$steps = [
    'Personal',
    'Contact',
    'Address',
    'Employment',
    'Education',
    'Beneficiaries',
    'Review'
];

?>

<section class="member-stepper">

    <div class="member-stepper__header">

        <span class="member-stepper__label">
            Step <span id="wizardStepNumber">1</span>
        </span>

        <h2
            id="wizardStepTitle"
            class="member-stepper__title">

            Personal Information

        </h2>

    </div>

    <div class="member-stepper__timeline">

        <?php foreach ($steps as $index => $step): ?>

            <div
                class="member-stepper__item <?= $index === 0 ? 'is-active' : '' ?>"
                data-step="<?= $index ?>">

                <div class="member-stepper__dot"></div>

                <span class="member-stepper__text">

                    <?= htmlspecialchars($step) ?>

                </span>

            </div>

        <?php endforeach; ?>

    </div>

</section>