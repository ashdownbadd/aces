<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

if (!function_exists('display_value')) {
    function display_value($value, $fallback = 'N/A')
    {
        return htmlspecialchars(!empty($value) ? $value : $fallback);
    }
}

?>

<div class="container container--lg">

    <div class="header-actions">

        <p>

            <a
                href="index.php?route=members"
                class="member-link">

                ← Back to Member Registry

            </a>

        </p>

    </div>

    <section class="profile-header">

        <div>

            <h1>

                <?=
                display_value(
                    ($member['first_name'] ?? '') . ' ' .
                        (!empty($member['middle_name'])
                            ? $member['middle_name'] . ' '
                            : '') .
                        ($member['last_name'] ?? '')
                );
                ?>

            </h1>

            <div class="profile-header__meta">

                ID:

                <strong>

                    <?= display_value($member['member_number'] ?? null); ?>

                </strong>

                |

                Type:

                <strong>

                    <?= display_value($member['membership_type'] ?? null); ?>

                </strong>

            </div>

        </div>

        <div class="profile-status">

            <?php

            $status = $member['status'] ?? 'inactive';

            if ($status === 'active') {

                $badgeClass = 'status-badge status-badge--active';
            } elseif ($status === 'inactive') {

                $badgeClass = 'status-badge status-badge--inactive';
            } else {

                $badgeClass = 'status-badge status-badge--disabled';
            }

            ?>

            <span class="<?= $badgeClass; ?>">

                <?= display_value($status); ?>

            </span>

            <div class="profile-joined">

                Joined:

                <?= display_value($member['date_of_membership'] ?? null); ?>

            </div>

        </div>

    </section>

    <section class="summary-grid">

        <article class="summary-card summary-card--success">

            <h3 class="summary-card__title">

                Share Capital Balance

            </h3>

            <div class="summary-card__value">

                $<?= number_format($member['ledger_balance'] ?? 0, 2); ?>

            </div>

            <a
                class="summary-card__link"
                href="index.php?route=ledger_statement&id=<?= $member['id'] ?? ''; ?>">

                View Detailed Statement →

            </a>

        </article>

        <article class="summary-card summary-card--warning">

            <h3 class="summary-card__title">

                Active Loan Status

            </h3>

            <?php if (!empty($member['active_loan'])): ?>

                <div class="summary-card__value">

                    $<?= number_format($member['active_loan']['remaining_balance'] ?? 0, 2); ?>

                </div>

                <p>

                    Next Due:

                    <strong>

                        <?= display_value($member['active_loan']['next_due_date'] ?? null); ?>

                    </strong>

                </p>

                <a
                    class="summary-card__link"
                    href="index.php?route=view_loan&id=<?= $member['active_loan']['id'] ?? ''; ?>">

                    View Schedule →

                </a>

            <?php else: ?>

                <p class="u-text-muted">

                    No active loan records.

                </p>

            <?php endif; ?>

        </article>

    </section>

    <div class="profile-grid">

        <div>

            <section class="info-card">

                <h3 class="info-card__title">

                    Personal Information

                </h3>

                <table class="info-table">

                    <tr>

                        <td>Date of Birth</td>

                        <td>
                            <strong>
                                <?= display_value($member['date_of_birth'] ?? null); ?>
                            </strong>
                        </td>

                    </tr>

                    <tr>

                        <td>Sex</td>

                        <td>
                            <strong>
                                <?= display_value($member['profile']['sex'] ?? null); ?>
                            </strong>
                        </td>

                    </tr>

                    <tr>

                        <td>Marital Status</td>

                        <td>
                            <strong>
                                <?= display_value($member['profile']['marital_status'] ?? null); ?>
                            </strong>
                        </td>

                    </tr>

                </table>

            </section>

            <section class="info-card">

                <h3 class="info-card__title">

                    Contact &amp; Address

                </h3>

                <div class="info-list">

                    <p>

                        <strong>Email:</strong>

                        <?= display_value($member['contact']['email'] ?? null); ?>

                    </p>

                    <p>

                        <strong>Primary Phone:</strong>

                        <?= display_value($member['contact']['phone_no_1'] ?? null); ?>

                    </p>

                    <p>

                        <strong>Secondary Phone:</strong>

                        <?= display_value($member['contact']['phone_no_2'] ?? null); ?>

                    </p>

                </div>

                <h4 class="info-subtitle">

                    Registered Address

                </h4>

                <?php if (empty($member['address']['town_city'])): ?>

                    <p class="u-text-muted">

                        No address info recorded.

                    </p>

                <?php else: ?>

                    <p>

                        <strong>Type:</strong>

                        <?= display_value($member['address']['address_type'] ?? 'Home'); ?>

                    </p>

                    <p class="address-text">

                        <?php

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

                        echo htmlspecialchars(implode(', ', $addressParts));

                        ?>

                    </p>

                <?php endif; ?>

            </section>

        </div>

        <div>

            <section class="record-card record-card--beneficiaries">

                <h3 class="record-card__title">

                    Beneficiaries

                </h3>

                <?php if (empty($member['beneficiaries'])): ?>

                    <p class="empty-state">

                        No beneficiaries registered.

                    </p>

                <?php else: ?>

                    <ul class="record-list">

                        <?php foreach ($member['beneficiaries'] as $ben): ?>

                            <li>

                                <strong>

                                    <?= display_value(
                                        ($ben['first_name'] ?? '') . ' ' .
                                            ($ben['last_name'] ?? '')
                                    ); ?>

                                </strong>

                                <span class="record-meta">

                                    (<?= display_value($ben['relation'] ?? null); ?>)

                                </span>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                <?php endif; ?>

            </section>

            <section class="record-card record-card--employment">

                <h3 class="record-card__title">

                    Employment / Experience

                </h3>

                <?php if (empty($member['experience'])): ?>

                    <p class="empty-state">

                        No experience records found.

                    </p>

                <?php else: ?>

                    <?php foreach ($member['experience'] as $exp): ?>

                        <div class="record-item">

                            <strong>

                                <?= display_value($exp['job_title'] ?? null); ?>

                            </strong>

                            at

                            <?= display_value($exp['organization'] ?? null); ?>

                            <br>

                            <span class="record-meta">

                                Duration:

                                <?= display_value($exp['date_started'] ?? 'Unknown'); ?>

                                to

                                <?= display_value($exp['date_ended'] ?? 'Present'); ?>

                            </span>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </section>

            <section class="record-card record-card--education">

                <h3 class="record-card__title">

                    Educational Background

                </h3>

                <?php if (empty($member['education'])): ?>

                    <p class="empty-state">

                        No educational records found.

                    </p>

                <?php else: ?>

                    <?php foreach ($member['education'] as $edu): ?>

                        <div class="record-item">

                            <strong>

                                <?= display_value($edu['program'] ?? null); ?>

                            </strong>

                            <br>

                            <?= display_value($edu['school_university'] ?? null); ?>

                            <br>

                            <span class="record-meta">

                                Ended:

                                <?= display_value($edu['date_ended'] ?? 'N/A'); ?>

                            </span>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </section>

        </div>

    </div>

</div>