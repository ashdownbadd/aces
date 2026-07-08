<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

$firstName = trim($member['first_name'] ?? '');
$middleName = trim($member['middle_name'] ?? '');
$lastName = trim($member['last_name'] ?? '');

$initials = strtoupper(
    substr($firstName, 0, 1) .
        substr($lastName, 0, 1)
);

$fullName = trim(
    $firstName .
        ($middleName ? ' ' . $middleName : '') .
        ($lastName ? ' ' . $lastName : '')
);

if ($fullName === '') {
    $fullName = 'Unknown Member';
}

$status = strtolower($member['status'] ?? 'inactive');

$badgeType = match ($status) {
    'active'   => 'success',
    'inactive' => 'warning',
    default    => 'danger'
};

?>

<section class="member-hero">

    <div class="member-hero__card">

        <div class="member-hero__avatar">

            <?= htmlspecialchars($initials ?: '?'); ?>

        </div>

        <h1 class="member-hero__name">

            <?= htmlspecialchars($fullName); ?>

        </h1>

        <p class="member-hero__membership">

            <?= display_value($member['membership_type'] ?? null); ?>

        </p>

        <p class="member-hero__number">

            Member #<?= display_value($member['member_number'] ?? null); ?>

        </p>

        <div class="member-hero__status">

            <?php

            c('badge', [

                'type' => $badgeType,

                'text' => ucfirst($status)

            ]);

            ?>

            <span class="member-hero__since">

                Member since
                <?= display_value($member['date_of_membership'] ?? null); ?>

            </span>

        </div>

    </div>

</section>