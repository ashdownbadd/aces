<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

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
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/input', [
            'id' => 'school',
            'label' => 'School / Institution',
            'name' => 'school',
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/input', [
            'id' => 'year_graduated',
            'type' => 'number',
            'label' => 'Year Graduated',
            'name' => 'year_graduated',
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
            'trim' => true,
            'transform' => 'capitalize',
            'maxlength' => 150
        ]);

        c('form/textarea', [
            'id' => 'education_remarks',
            'label' => 'Educational Remarks',
            'name' => 'education_remarks',
            'rows' => 4,
            'placeholder' => 'Additional educational information (optional)...'
        ]);

        ?>

    </div>

</div>