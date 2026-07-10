<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="member-step__content">

    <h2 class="member-step__title">
        Personal Information
    </h2>

    <p class="member-step__description">
        Enter the member's basic personal information.
    </p>

    <div class="form-grid form-grid--2">

        <?php

        c('form/input', [
            'label' => 'First Name',
            'name' => 'first_name',
            'placeholder' => 'Juan',
            'required' => true,
            'maxlength' => 100
        ]);

        c('form/input', [
            'label' => 'Middle Name',
            'name' => 'middle_name',
            'placeholder' => 'Santos',
            'maxlength' => 100
        ]);

        c('form/input', [
            'label' => 'Last Name',
            'name' => 'last_name',
            'placeholder' => 'Dela Cruz',
            'required' => true,
            'maxlength' => 100
        ]);

        c('form/input', [
            'label' => 'Suffix',
            'name' => 'suffix',
            'placeholder' => 'Jr.',
            'maxlength' => 20
        ]);

        c('form/input', [
            'type' => 'date',
            'label' => 'Birth Date',
            'name' => 'date_of_birth',
            'required' => true,
            'max' => date('Y-m-d')
        ]);

        c('form/input', [
            'label' => 'Birth Place',
            'name' => 'birth_place',
            'placeholder' => 'Quezon City',
            'required' => true,
            'maxlength' => 150
        ]);

        c('form/select', [
            'label' => 'Sex',
            'name' => 'sex',
            'required' => true,
            'options' => [
                '' => 'Select Sex',
                'Male' => 'Male',
                'Female' => 'Female'
            ]
        ]);

        c('form/select', [
            'label' => 'Civil Status',
            'name' => 'marital_status',
            'required' => true,
            'options' => [
                '' => 'Select Civil Status',
                'Single' => 'Single',
                'Married' => 'Married',
                'Widowed' => 'Widowed',
                'Separated' => 'Separated'
            ]
        ]);

        ?>

    </div>

    <div class="form-grid">

        <?php

        c('form/select', [
            'label' => 'Membership',
            'name' => 'membership_type',
            'required' => true,
            'help' => 'Select the cooperative membership type.',
            'options' => [
                '' => 'Select Membership',
                'Regular' => 'Regular',
                'Associate' => 'Associate'
            ]
        ]);

        ?>

    </div>

</div>