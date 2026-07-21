<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

    <?php

    c('page_header', [
        'title' => 'Create Loan',
        'description' => 'Create a new cooperative loan account and automatically generate its amortization schedule.'
    ]);

    ?>

    <?php c('flash_messages'); ?>

    <div class="page__actions">

        <a
            href="<?= url('amortization_dashboard') ?>"
            class="btn btn--secondary">

            <i class="fas fa-arrow-left"></i>

            Back to Loans

        </a>

    </div>

    <form
        action="<?= url('create_loan') ?>"
        method="POST"
        enctype="multipart/form-data"
        class="form">

        <div class="page__content">

            <!-- =======================================================
                 BORROWER INFORMATION
            ======================================================== -->

            <section class="card">

                <div class="card__header">

                    <div>

                        <h2 class="card__title">

                            <i class="fas fa-user"></i>

                            Borrower Information

                        </h2>

                        <p class="card__subtitle">

                            Select the cooperative member and configure the basic details of the loan.

                        </p>

                    </div>

                </div>

                <div class="card__body">

                    <div class="form-grid form-grid--3">

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="member_id">

                                Member

                            </label>

                            <select
                                class="form-control"
                                id="member_id"
                                name="member_id"
                                required>

                                <option value="">

                                    -- Select Member --

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

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="loan_type">

                                Loan Type

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

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="principal">

                                Principal Amount (₱)

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

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="interest_rate">

                                Interest Rate (%)

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

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="terms">

                                Loan Term (Months)

                            </label>

                            <input
                                class="form-control"
                                type="number"
                                id="terms"
                                name="terms"
                                required
                                oninput="calculateLiveDeductions()">

                        </div>

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="start_date">

                                Loan Start Date

                            </label>

                            <input
                                class="form-control"
                                type="date"
                                id="start_date"
                                name="start_date"
                                required>

                        </div>

                    </div>

                </div>

            </section>

            <!-- =======================================================
                 REPAYMENT DETAILS
            ======================================================== -->

            <section class="card">

                <div class="card__header">

                    <div>

                        <h2 class="card__title">

                            <i class="fas fa-credit-card"></i>

                            Repayment Details

                        </h2>

                        <p class="card__subtitle">

                            Configure how this loan will be repaid and secured.

                        </p>

                    </div>

                </div>

                <div class="card__body">

                    <div class="form-grid form-grid--3">

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="collateral">

                                Collateral

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

                        <div class="form-group">

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

                                    Straight-line

                                </option>

                                <option value="Diminishing balance">

                                    Diminishing Balance

                                </option>

                                <option value="Manual">

                                    Manual

                                </option>

                            </select>

                        </div>

                        <div
                            id="frequency_panel"
                            class="form-group loan-hidden">

                            <label
                                class="form-label"
                                for="payment_frequency">

                                Payment Frequency

                            </label>

                            <select
                                class="form-control"
                                id="payment_frequency"
                                name="payment_frequency">

                                <option value="Monthly">

                                    Monthly

                                </option>

                                <option value="Bi-Monthly">

                                    Bi-Monthly

                                </option>

                                <option value="Weekly">

                                    Weekly

                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </section>

            <!-- =======================================================
                 REAL PROPERTY INFORMATION
            ======================================================== -->

            <section
                id="real_property_panel"
                class="card loan-hidden">

                <div class="card__header">

                    <div>

                        <h2 class="card__title">

                            <i class="fas fa-house"></i>

                            Real Property Information

                        </h2>

                        <p class="card__subtitle">

                            Complete the required information for loans secured by real property.

                        </p>

                    </div>

                </div>

                <div class="card__body">

                    <div class="form-grid form-grid--3">

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="tct_no">

                                TCT Number

                            </label>

                            <input
                                class="form-control"
                                type="text"
                                id="tct_no"
                                name="tct_no">

                        </div>

                        <div class="form-group">

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

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="real_property_status">

                                Tax Payment Status

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

                    <div class="form-grid form-grid--2">

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="undertaking_doc">

                                Undertaking Document (PDF)

                            </label>

                            <input
                                class="form-control"
                                type="file"
                                id="undertaking_doc"
                                name="undertaking_doc"
                                accept="application/pdf">

                        </div>

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="deed_of_rights_doc">

                                Assignment of Deed of Rights (PDF)

                            </label>

                            <input
                                class="form-control"
                                type="file"
                                id="deed_of_rights_doc"
                                name="deed_of_rights_doc"
                                accept="application/pdf">

                        </div>

                    </div>

                </div>

            </section>

            <!-- =======================================================
                 LOAN SUMMARY
            ======================================================== -->

            <section
                id="deductions_panel"
                class="card">

                <div class="card__header">

                    <div>

                        <h2 class="card__title">

                            <i class="fas fa-chart-pie"></i>

                            Loan Summary

                        </h2>

                        <p class="card__subtitle">

                            Estimated deductions before loan proceeds are released.

                        </p>

                    </div>

                </div>

                <div class="card__body">

                    <div class="stats-grid">

                        <div class="stat-card stat-card--warning">

                            <div class="stat-card__body">

                                <p class="stat-card__title">

                                    Processing Fee

                                </p>

                                <h3
                                    id="lbl_processing"
                                    class="stat-card__value">

                                    ₱0.00

                                </h3>

                                <p class="stat-card__subtitle">

                                    2% of principal amount

                                </p>

                            </div>

                        </div>

                        <div class="stat-card stat-card--info">

                            <div class="stat-card__body">

                                <p class="stat-card__title">

                                    Insurance

                                </p>

                                <h3
                                    id="lbl_insurance"
                                    class="stat-card__value">

                                    ₱0.00

                                </h3>

                                <p class="stat-card__subtitle">

                                    Based on loan term

                                </p>

                            </div>

                        </div>

                        <div class="stat-card stat-card--primary">

                            <div class="stat-card__body">

                                <p class="stat-card__title">

                                    Notarial Fee

                                </p>

                                <h3
                                    id="lbl_notarial"
                                    class="stat-card__value">

                                    ₱0.00

                                </h3>

                                <p class="stat-card__subtitle">

                                    Documentation fee

                                </p>

                            </div>

                        </div>

                        <div class="stat-card stat-card--success">

                            <div class="stat-card__body">

                                <p class="stat-card__title">

                                    Net Proceeds

                                </p>

                                <h3
                                    id="lbl_net"
                                    class="stat-card__value">

                                    ₱0.00

                                </h3>

                                <p class="stat-card__subtitle">

                                    Estimated amount to be released

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card__footer">

                    <a
                        href="<?= url('amortization_dashboard') ?>"
                        class="btn btn--secondary">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn--success btn--lg">

                        <i class="fas fa-hand-holding-dollar"></i>

                        Create Loan

                    </button>

                </div>

            </section>

        </div>

    </form>

</div>