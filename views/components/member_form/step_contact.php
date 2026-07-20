<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$member = $member ?? [];
$contact = $member['contact'] ?? [];

?>

<div class="member-step__content">

    <h2 class="member-step__title">
        Contact Information
    </h2>

    <p class="member-step__description">
        Provide the member's contact information.
    </p>

    <div class="form-grid">

        <?php

        c('form/input', [
            'type' => 'email',
            'label' => 'Email Address',
            'name' => 'email',
            'value' => $contact['email'] ?? '',
            'placeholder' => 'juan.delacruz@example.com',
            'autocomplete' => 'email',
            'maxlength' => 255
        ]);

        ?>

    </div>

    <div class="form-grid form-grid--2">

        <?php

        c('form/input', [
            'type' => 'tel',
            'label' => 'Primary Phone',
            'name' => 'phone_no_1',
            'value' => $contact['phone_no_1'] ?? '',
            'required' => true,
            'placeholder' => '0917 123 4567',
            'autocomplete' => 'tel',
            'inputmode' => 'tel',
            'maxlength' => 20,
            'mask' => 'mobile'
        ]);

        c('form/input', [
            'type' => 'tel',
            'label' => 'Secondary Phone',
            'name' => 'phone_no_2',
            'value' => $contact['phone_no_2'] ?? '',
            'placeholder' => '0917 123 4567',
            'autocomplete' => 'tel',
            'inputmode' => 'tel',
            'maxlength' => 20,
            'mask' => 'mobile'
        ]);

        c('form/input', [
            'type' => 'tel',
            'label' => 'Primary Telephone',
            'name' => 'telephone_no_1',
            'value' => $contact['telephone_no_1'] ?? '',
            'placeholder' => '(02) 8123 4567',
            'autocomplete' => 'tel',
            'inputmode' => 'tel',
            'maxlength' => 20,
            'mask' => 'telephone'
        ]);

        c('form/input', [
            'type' => 'tel',
            'label' => 'Secondary Telephone',
            'name' => 'telephone_no_2',
            'value' => $contact['telephone_no_2'] ?? '',
            'placeholder' => '(02) 8123 4567',
            'autocomplete' => 'tel',
            'inputmode' => 'tel',
            'maxlength' => 20,
            'mask' => 'telephone'
        ]);

        ?>

    </div>

</div>