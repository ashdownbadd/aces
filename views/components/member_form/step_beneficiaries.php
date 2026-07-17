<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="member-step__content">

    <p class="member-step__description">
        Add one or more beneficiaries. The total allocation must equal 100%.
    </p>

    <div id="beneficiaryList"></div>

    <div class="member-beneficiaries__footer">

        <div class="member-beneficiaries__allocation">

            <span>Total Allocation</span>

            <strong id="beneficiaryAllocation">0%</strong>

        </div>

        <button
            type="button"
            id="addBeneficiary"
            class="btn btn--secondary">

            <i class="fas fa-plus"></i>

            Add Beneficiary

        </button>

    </div>

</div>