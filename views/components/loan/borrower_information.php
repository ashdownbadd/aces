<section class="card">

    <div class="card__header">

        <div>

            <h2 class="card__title">

                <i class="fas fa-user"></i>

                Borrower Information

            </h2>

        </div>

    </div>

    <div class="card__body">

        <div class="loan-grid--header">

            <div class="form-group form-group--span-2">

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

        </div>

        <div class="loan-grid--details">

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
                    placeholder="0%"
                    required>

            </div>

            <div class="form-group">

                <label
                    class="form-label"
                    for="terms">

                    Loan Term

                </label>

                <input
                    class="form-control"
                    type="number"
                    id="terms"
                    name="terms"
                    placeholder="12"
                    required>

            </div>

        </div>

        <div class="form-grid">

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