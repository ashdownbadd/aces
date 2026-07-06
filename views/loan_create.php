<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page loan-page">

    <?php

    c('page_header', [

        'title' => 'Generate Amortization Schedule',

        'description' => 'Setup parameters below to automatically formulate an explicit financial schedule matrix matching portfolio logic rules.'

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <p class="page__back">

        <a href="<?= url('amortization_dashboard') ?>">

            ← Back to Loans Dashboard

        </a>

    </p>

    <form
        action="<?= url('create_loan') ?>"
        method="POST"
        enctype="multipart/form-data"
        class="loan-form">

        <div class="loan-section">

            <label
                class="form-label"
                for="member_id">
                Cooperative Member Profile
            </label>

            <select
                class="form-control"
                id="member_id"
                name="member_id"
                required>

                <option value="">

                    -- Select Target Account Owner --

                </option>

                <?php foreach ($members as $member): ?>

                    <option value="<?= (int) $member['id'] ?>">

                        <?= htmlspecialchars(
                            $member['last_name']
                                . ', '
                                . $member['first_name']
                                . ' ('
                                . $member['member_number']
                                . ')'
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="loan-row">

            <div class="loan-column">

                <label
                    class="form-label"
                    for="loan_type">
                    Loan Type Classification
                </label>

                <select
                    class="form-control"
                    id="loan_type"
                    name="loan_type"
                    onchange="handleLoanTypeChange()"
                    required>

                    <option value="Personal Loan">Personal Loan</option>
                    <option value="Bridge Financing">Bridge Financing</option>
                    <option value="Investment Loan">Investment Loan</option>
                    <option value="Pension Loan">Pension Loan</option>
                    <option value="Productivity Loan">Productivity Loan</option>
                    <option value="Salary Loan">Salary Loan</option>
                    <option value="Micro-Finance Loan">Micro-Finance Loan</option>

                </select>

            </div>

            <div class="loan-column">

                <label
                    class="form-label"
                    for="collateral">
                    Asset Collateral Class
                </label>

                <select
                    class="form-control"
                    id="collateral"
                    name="collateral"
                    onchange="handleCollateralChange()"
                    required>

                    <option value="Post-Dated Check">

                        Post-Dated Check

                    </option>

                    <option value="Real Property">

                        Real Property

                    </option>

                    <option value="Chattels / Movable Assets">

                        Chattels / Movable Assets

                    </option>

                </select>

            </div>

        </div>

        <div
            id="real_property_panel"
            class="loan-panel loan-panel--property">

            <h3 class="loan-panel__title">

                Real Property Registration Assets

            </h3>

            <div class="loan-row">

                <div class="loan-column">

                    <label
                        class="form-label"
                        for="tct_no">
                        TCT No.
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        id="tct_no"
                        name="tct_no">

                </div>

                <div class="loan-column">

                    <label
                        class="form-label"
                        for="tax_declaration_no">
                        Tax Declaration No.
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        id="tax_declaration_no"
                        name="tax_declaration_no">

                </div>

                <div class="loan-column">

                    <label
                        class="form-label"
                        for="real_property_status">
                        Tax Payments Status
                    </label>

                    <select
                        class="form-control"
                        id="real_property_status"
                        name="real_property_status">

                        <option value="Updated">

                            Updated

                        </option>

                        <option value="Pending">

                            Pending

                        </option>

                        <option value="Overdue">

                            Overdue

                        </option>

                    </select>

                </div>

            </div>

            <div class="loan-section">

                <label
                    class="form-label"
                    for="undertaking_doc">
                    Undertaking Document (PDF Only)
                </label>

                <input
                    class="form-control"
                    type="file"
                    id="undertaking_doc"
                    name="undertaking_doc"
                    accept="application/pdf">

            </div>

            <div class="loan-section">

                <label
                    class="form-label"
                    for="deed_of_rights_doc">
                    Assignment of Deed of Rights (PDF Only)
                </label>

                <input
                    class="form-control"
                    type="file"
                    id="deed_of_rights_doc"
                    name="deed_of_rights_doc"
                    accept="application/pdf">

            </div>

        </div>

        <div class="loan-row">

            <div class="loan-column">

                <label
                    class="form-label"
                    for="amortization_type">
                    Amortization Method
                </label>

                <select
                    class="form-control"
                    id="amortization_type"
                    name="amortization_type"
                    onchange="handleAmortTypeChange()"
                    required>

                    <option value="Straight-line">

                        Straight-line Mode

                    </option>

                    <option value="Diminishing balance">

                        Diminishing Balance Mode

                    </option>

                    <option value="Manual">

                        Manual Installment Payment

                    </option>

                </select>

            </div>

            <div
                id="frequency_panel"
                class="loan-column loan-hidden">

                <label
                    class="form-label"
                    for="payment_frequency">
                    Micro-Finance Frequency Multiplier
                </label>

                <select
                    class="form-control"
                    id="payment_frequency"
                    name="payment_frequency">

                    <option value="Monthly">

                        Monthly Structure

                    </option>

                    <option value="Bi-Monthly">

                        Bi-Monthly (+15 Days Split)

                    </option>

                    <option value="Weekly">

                        Weekly Cycle (+7 Days Split)

                    </option>

                </select>

            </div>

        </div>

        <div class="loan-row">

            <div class="loan-column">

                <label
                    class="form-label"
                    for="principal">
                    Principal Core Sum (₱)
                </label>

                <input
                    class="form-control"
                    type="number"
                    id="principal"
                    name="principal"
                    step="0.01"
                    required
                    oninput="calculateLiveDeductions()">

            </div>

            <div class="loan-column">

                <label
                    class="form-label"
                    for="interest_rate">
                    Interest Rate (% Per Month)
                </label>

                <input
                    class="form-control"
                    type="number"
                    id="interest_rate"
                    name="interest_rate"
                    step="0.01"
                    required
                    oninput="flagManualInterest()">

            </div>

        </div>

        <div class="loan-row">

            <div class="loan-column">

                <label
                    class="form-label"
                    for="terms">
                    Term Duration (Months)
                </label>

                <input
                    class="form-control"
                    type="number"
                    id="terms"
                    name="terms"
                    required
                    oninput="calculateLiveDeductions()">

            </div>

            <div class="loan-column">

                <label
                    class="form-label"
                    for="start_date">
                    Schedule Start Release Date
                </label>

                <input
                    class="form-control"
                    type="date"
                    id="start_date"
                    name="start_date"
                    required>

            </div>

        </div>

        <div
            id="manual_payment_panel"
            class="loan-section loan-hidden">

            <label
                class="form-label"
                for="manual_payment">
                Explicit Target Amount Per Period (₱)
            </label>

            <input
                class="form-control"
                type="number"
                id="manual_payment"
                name="manual_payment"
                step="0.01"
                value="0.00">

        </div>

        <div
            id="deductions_panel"
            class="loan-panel loan-panel--summary">

            <h3 class="loan-panel__title">

                Computed Capital Deductions

            </h3>

            <div class="loan-summary__row">

                <span>

                    Processing Fee (2%)

                </span>

                <strong id="lbl_processing">

                    ₱0.00

                </strong>

            </div>

            <div class="loan-summary__row">

                <span>

                    Mutual Insurance Protection

                </span>

                <strong id="lbl_insurance">

                    ₱0.00

                </strong>

            </div>

            <div class="loan-summary__row">

                <span>

                    Notarial Fee

                </span>

                <strong id="lbl_notarial">

                    ₱0.00

                </strong>

            </div>

            <div class="loan-summary__total">

                Projected Net Loan Proceeds

                <span id="lbl_net">

                    ₱0.00

                </span>

            </div>

        </div>

        <div class="loan-actions">

            <button
                type="submit"
                class="btn btn--success loan-submit">

                Establish Account & Commit Amortization Schedule

            </button>

        </div>

    </form>

</div>

<script src="assets/js/loan-create.js"></script>