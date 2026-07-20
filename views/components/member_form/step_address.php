<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$member = $member ?? [];
$address = $member['address'] ?? [];

?>

<div class="member-step__content">

    <h2 class="member-step__title">
        Address Information
    </h2>

    <p class="member-step__description">
        Provide the member's residential address.
    </p>

    <div class="form-grid form-grid--2">

        <?php

        c('form/input', [
            'label' => 'House Number',
            'name' => 'house_number',
            'value' => $address['house_number'] ?? '',
            'placeholder' => '123',
            'maxlength' => 100
        ]);

        c('form/input', [
            'label' => 'Street',
            'name' => 'street',
            'value' => $address['street'] ?? '',
            'placeholder' => 'Rizal Street',
            'maxlength' => 150
        ]);

        ?>

    </div>

    <div class="form-grid form-grid--2">

        <?php

        c('form/input', [
            'label' => 'Barangay',
            'name' => 'barangay',
            'value' => $address['barangay'] ?? '',
            'placeholder' => 'Barangay San Isidro',
            'maxlength' => 150
        ]);

        c('form/input', [
            'label' => 'Municipality / City',
            'name' => 'town_city',
            'value' => $address['town_city'] ?? '',
            'placeholder' => 'Quezon City',
            'maxlength' => 150
        ]);

        ?>

    </div>

    <div class="form-grid form-grid--2">

        <?php

        c('form/input', [
            'label' => 'Province',
            'name' => 'province',
            'value' => $address['province'] ?? '',
            'placeholder' => 'Metro Manila',
            'maxlength' => 150
        ]);

        c('form/input', [
            'label' => 'Region',
            'name' => 'region',
            'value' => $address['region'] ?? '',
            'placeholder' => 'NCR',
            'maxlength' => 150
        ]);

        ?>

    </div>

</div>