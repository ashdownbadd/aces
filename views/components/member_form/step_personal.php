<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$member = $member ?? [];
$profile = $member['profile'] ?? [];

?>

<div class="member-step__content">

    <p class="member-step__description">
        Enter the member's basic personal information.
    </p>

    <div class="form-grid form-grid--2">

        <?php

        c('form/input', [
            'label' => 'First Name',
            'name' => 'first_name',
            'value' => $member['first_name'] ?? '',
            'required' => true,
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 100
        ]);

        c('form/input', [
            'label' => 'Middle Name',
            'name' => 'middle_name',
            'value' => $member['middle_name'] ?? '',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 100
        ]);

        c('form/input', [
            'label' => 'Last Name',
            'name' => 'last_name',
            'value' => $member['last_name'] ?? '',
            'required' => true,
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 100
        ]);

        c('form/input', [
            'label' => 'Suffix',
            'name' => 'suffix',
            'value' => $member['suffix'] ?? '',
            'trim' => true,
            'transform' => 'uppercase',
            'maxlength' => 20
        ]);

        c('form/input', [
            'type' => 'date',
            'label' => 'Birth Date',
            'name' => 'date_of_birth',
            'value' => $member['date_of_birth'] ?? '',
            'required' => true,
            'max' => date('Y-m-d')
        ]);

        c('form/input', [
            'label' => 'Birth Place',
            'name' => 'birthplace',
            'value' => $member['profile']['birthplace'] ?? '',
            'required' => true,
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/select', [
            'label' => 'Sex',
            'name' => 'sex',
            'value' => $profile['sex'] ?? '',
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
            'value' => $profile['marital_status'] ?? '',
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
            'value' => $member['membership_type'] ?? '',
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