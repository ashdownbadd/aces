<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<section class="member-section">

    <h2 class="member-section__title">

        Personal Information

    </h2>

    <div class="member-definition-list">

        <div class="member-definition-list__row">

            <span>Date of Birth</span>

            <strong><?= display_value($member['date_of_birth'] ?? null); ?></strong>

        </div>

        <div class="member-definition-list__row">

            <span>Sex</span>

            <strong><?= display_value($member['profile']['sex'] ?? null); ?></strong>

        </div>

        <div class="member-definition-list__row">

            <span>Marital Status</span>

            <strong><?= display_value($member['profile']['marital_status'] ?? null); ?></strong>

        </div>

    </div>

</section>