<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$addressParts = array_filter([

    $member['address']['house_number'] ?? '',
    $member['address']['street'] ?? '',
    !empty($member['address']['barangay']) ? 'Brgy. ' . $member['address']['barangay'] : '',
    $member['address']['zone'] ?? '',
    $member['address']['district'] ?? '',
    $member['address']['town_city'] ?? '',
    $member['address']['province'] ?? '',
    $member['address']['region'] ?? ''

], 'trim');

?>

<section class="member-section">

    <h2 class="member-section__title">

        Contact Information

    </h2>

    <div class="member-definition-list">

        <div class="member-definition-list__row">

            <span>Email</span>

            <strong><?= display_value($member['contact']['email'] ?? null); ?></strong>

        </div>

        <div class="member-definition-list__row">

            <span>Primary Phone</span>

            <strong><?= display_value($member['contact']['phone_no_1'] ?? null); ?></strong>

        </div>

        <div class="member-definition-list__row">

            <span>Secondary Phone</span>

            <strong><?= display_value($member['contact']['phone_no_2'] ?? null); ?></strong>

        </div>

        <div class="member-definition-list__row">

            <span>Address</span>

            <strong>

                <?= empty($addressParts)
                    ? 'No address recorded'
                    : htmlspecialchars(implode(', ', $addressParts)); ?>

            </strong>

        </div>

    </div>

</section>