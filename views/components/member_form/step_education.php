<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$member = $member ?? [];
$education = $member['education'][0] ?? [];

?>

<div class="member-step__content">

    <p class="member-step__description">
        Provide the member's highest educational attainment and related academic information.
    </p>

    <div class="form-grid form-grid--2">

        <?php

        c('form/select', [
            'id' => 'education_level',
            'label' => 'Highest Educational Attainment',
            'name' => 'education_level',
            'value' => $education['education_level'] ?? '',
            'required' => true,
            'rules' => [
                'required'
            ],
            'options' => [
                '' => 'Select Educational Attainment',
                'Elementary' => 'Elementary',
                'High School' => 'High School',
                'Senior High School' => 'Senior High School',
                'Vocational' => 'Vocational',
                'College' => 'College',
                'Master\'s Degree' => 'Master\'s Degree',
                'Doctorate' => 'Doctorate'
            ]
        ]);

        c('form/input', [
            'id' => 'course',
            'label' => 'Course / Degree',
            'name' => 'course',
            'value' => $education['course'] ?? '',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/input', [
            'id' => 'school',
            'label' => 'School / Institution',
            'name' => 'school',
            'value' => $education['school'] ?? '',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/input', [
            'id' => 'year_graduated',
            'type' => 'number',
            'label' => 'Year Graduated',
            'name' => 'year_graduated',
            'value' => $education['year_graduated'] ?? '',
            'min' => '1950',
            'max' => date('Y')
        ]);

        ?>

    </div>

    <div class="form-grid">

        <?php

        c('form/input', [
            'id' => 'honors',
            'label' => 'Honors / Awards',
            'name' => 'honors',
            'value' => $education['honors'] ?? '',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/textarea', [
            'id' => 'education_remarks',
            'label' => 'Educational Remarks',
            'name' => 'education_remarks',
            'value' => $education['education_remarks'] ?? '',
            'rows' => 4,
            'placeholder' => 'Additional educational information (Optional)...'
        ]);

        ?>

    </div>

</div>