<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$addressParts = array_filter([

    $member['address']['house_number'] ?? '',
    $member['address']['street'] ?? '',
    !empty($member['address']['barangay'])
        ? 'Brgy. ' . $member['address']['barangay']
        : '',
    $member['address']['zone'] ?? '',
    $member['address']['district'] ?? '',
    $member['address']['town_city'] ?? '',
    $member['address']['province'] ?? '',
    $member['address']['region'] ?? ''

], 'trim');

$address = empty($addressParts)
    ? 'No address recorded'
    : htmlspecialchars(implode(', ', $addressParts));

ob_start();

c('details_list', [

    'items' => [

        'Date of Birth' =>
        display_value($member['date_of_birth'] ?? null),

        'Sex' =>
        display_value($member['profile']['sex'] ?? null),

        'Marital Status' =>
        display_value($member['profile']['marital_status'] ?? null),

        'Email' =>
        display_value($member['contact']['email'] ?? null),

        'Primary Phone' =>
        display_value($member['contact']['phone_no_1'] ?? null),

        'Secondary Phone' =>
        display_value($member['contact']['phone_no_2'] ?? null),

        'Address' =>
        $address

    ]

]);

$body = ob_get_clean();

c('card', [

    'title' => 'Member Information',

    'body' => $body

]);
