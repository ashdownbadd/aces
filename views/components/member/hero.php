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
    'pending'  => 'info',
    'deceased' => 'danger',
    default    => 'danger'
};

?>

<section class="member-hero">

    <div class="member-hero__avatar">

        <?= htmlspecialchars($initials ?: '?'); ?>

    </div>

    <div class="member-hero__content">

        <div class="member-hero__top">

            <div class="member-hero__identity">

                <div class="member-hero__title">

                    <h1 class="member-hero__name">

                        <?= htmlspecialchars($fullName); ?>

                    </h1>

                    <?php

                    c('badge', [
                        'type' => $badgeType,
                        'text' => ucfirst($status)
                    ]);

                    ?>

                </div>

                <p class="member-hero__membership">

                    <?= display_value($member['membership_type']); ?> Member

                </p>

            </div>

            <div class="member-hero__actions">

                <?php

                c('button', [
                    'href' => 'index.php?route=member_edit&id=' . ($member['id'] ?? ''),
                    'text' => 'Edit Member',
                    'icon' => 'fas fa-pen',
                    'type' => 'primary'
                ]);

                ?>

            </div>

        </div>

        <div class="member-hero__meta">

            <div class="member-hero__meta-card">

                <i class="fas fa-id-card"></i>

                <span class="member-hero__meta-label">

                    Member Number

                </span>

                <strong>

                    #<?= display_value($member['member_number']); ?>

                </strong>

            </div>

            <div class="member-hero__meta-card">

                <i class="fas fa-calendar-alt"></i>

                <span class="member-hero__meta-label">

                    Member Since

                </span>

                <strong>

                    <?= display_value($member['date_of_membership']); ?>

                </strong>

            </div>

            <div class="member-hero__meta-card">

                <i class="fas fa-user-friends"></i>

                <span class="member-hero__meta-label">

                    Membership

                </span>

                <strong>

                    <?= display_value($member['membership_type']); ?>

                </strong>

            </div>

        </div>

    </div>

</section>