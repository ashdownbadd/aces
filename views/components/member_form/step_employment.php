<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$member = $member ?? [];
$employment = $member['employment'] ?? [];

?>

<div class="member-step__content">

    <p class="member-step__description">
        Tell us about the member's current employment or primary source of income.
    </p>

    <div class="form-grid form-grid--2">

        <?php

        c('form/select', [
            'id' => 'employment_status',
            'label' => 'Employment Status',
            'name' => 'employment_status',
            'value' => $employment['employment_status'] ?? '',
            'required' => true,
            'rules' => [
                'required'
            ],
            'options' => [
                '' => 'Select Employment Status',
                'Employed' => 'Employed',
                'Self-employed' => 'Self-employed',
                'Unemployed' => 'Unemployed',
                'Retired' => 'Retired',
                'Student' => 'Student'
            ]
        ]);

        c('form/input', [
            'id' => 'occupation',
            'label' => 'Occupation',
            'name' => 'occupation',
            'value' => $employment['occupation'] ?? '',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 100
        ]);

        c('form/input', [
            'id' => 'employer_name',
            'label' => 'Employer',
            'name' => 'employer_name',
            'value' => $employment['employer_name'] ?? '',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/input', [
            'id' => 'monthly_income',
            'label' => 'Monthly Income',
            'name' => 'monthly_income',
            'value' => $employment['monthly_income'] ?? '',
            'inputmode' => 'decimal',
            'placeholder' => '0.00'
        ]);

        ?>

    </div>

    <div class="form-grid">

        <?php

        c('form/input', [
            'id' => 'employer_address',
            'label' => 'Employer Address',
            'name' => 'employer_address',
            'value' => $employment['employer_address'] ?? '',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 200
        ]);

        c('form/textarea', [
            'label' => 'Employment Remarks',
            'name' => 'employment_remarks',
            'value' => $employment['employment_remarks'] ?? '',
            'rows' => 4,
            'placeholder' => 'Additional employment information (optional)...'
        ]);

        ?>

    </div>

</div>