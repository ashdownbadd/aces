<section class="card">

    <div class="card__header">

        <div>

            <h2 class="card__title">

                <i class="fas fa-file-signature"></i>

                Loan Details

            </h2>

            <p class="card__subtitle">

                Configure the loan information.

            </p>

        </div>

    </div>

    <div class="card__body">

        <div class="form-grid form-grid--2">

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
                    required>
                    <option value="" selected disabled>
                        -- Select Loan Type --
                    </option>

                    <option value="Bridge Financing">Bridge Financing</option>
                    <option value="Investment Loan">Investment Loan</option>
                    <option value="Pension Loan">Pension Loan</option>
                    <option value="Productivity Loan">Productivity Loan</option>
                    <option value="Personal Loan">Personal Loan</option>
                    <option value="Salary Loan">Salary Loan</option>
                    <option value="Micro-Finance Loan">Micro-Finance Loan</option>

                </select>

            </div>

            <div class="form-group">

                <label
                    class="form-label"
                    for="collateral">

                    Collateral

                </label>

                <select
                    class="form-control"
                    id="collateral"
                    name="collateral">

                    <option value="">
                        -- Select Collateral --
                    </option>

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
                    for="principal">

                    Principal Amount

                </label>

                <input
                    class="form-control"
                    type="number"
                    id="principal"
                    name="principal"
                    step="0.01"
                    placeholder="₱10,000.00"
                    required>

            </div>

            <div class="form-group">

                <label
                    class="form-label"
                    for="interest_rate">

                    Interest Rate

                </label>

                <input
                    class="form-control"
                    type="number"
                    id="interest_rate"
                    name="interest_rate"
                    step="0.01"
                    placeholder="0.00"
                    required>

            </div>

        </div>

        <div
            id="real_property_fields"
            class="loan-real-property"
            style="display:none;">

            <div class="form-grid form-grid--2">

                <div class="form-group">

                    <label
                        class="form-label"
                        for="tct_number">

                        TCT No.

                    </label>

                    <input
                        class="form-control"
                        type="text"
                        id="tct_number"
                        name="tct_number">

                </div>

                <div class="form-group">

                    <label
                        class="form-label"
                        for="tax_declaration_number">

                        Tax Declaration No.

                    </label>

                    <input
                        class="form-control"
                        type="text"
                        id="tax_declaration_number"
                        name="tax_declaration_number">

                </div>

                <div class="form-group">

                    <label
                        class="form-label"
                        for="undertaking_file">

                        Undertaking (PDF)

                    </label>

                    <input
                        class="form-control"
                        type="file"
                        id="undertaking_file"
                        name="undertaking_file"
                        accept=".pdf">

                </div>

                <div class="form-group">

                    <label
                        class="form-label"
                        for="assignment_deed_file">

                        Assignment of Deed (PDF)

                    </label>

                    <input
                        class="form-control"
                        type="file"
                        id="assignment_deed_file"
                        name="assignment_deed_file"
                        accept=".pdf">

                </div>

            </div>

        </div>

    </div>

</section>