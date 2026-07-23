<div class="card">

    <div class="card__header">

        <div>

            <h2 class="card__title">

                <i class="fas fa-calendar-days"></i>

                Schedule

            </h2>

            <p class="card__subtitle">

                Configure how this loan will be repaid.

            </p>

        </div>

    </div>

    <div class="card__body">

        <div class="form-grid form-grid--2">

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

            <div class="form-group">

                <label
                    class="form-label"
                    for="payment_frequency">

                    Payment Frequency

                </label>

                <select id="payment_frequency" name="payment_frequency">

                    <option value="monthly">Monthly</option>

                    <option value="semi_monthly">Semi-Monthly</option>

                    <option value="bi_weekly">Bi-Weekly</option>

                    <option value="weekly">Weekly</option>

                    <option value="daily">Daily</option>

                </select>

            </div>

            <div class="form-group">

                <label
                    class="form-label"
                    for="amortization_type">

                    Amortization Method

                </label>

                <select id="amortization_type" name="amortization_type">

                    <option value="straight_line">
                        Straight Line
                    </option>

                    <option value="diminishing_balance">
                        Diminishing Balance
                    </option>

                    <option value="manual">
                        Manual
                    </option>

                </select>

            </div>

        </div>

        <div
            id="manual_interest_container"
            style="display:none;">

            <div class="form-grid">

                <div class="form-group">

                    <label
                        class="form-label"
                        for="manual_interest">

                        Manual Interest Amount

                    </label>

                    <input
                        class="form-control"
                        type="number"
                        id="manual_interest"
                        name="manual_interest"
                        step="0.01">

                </div>

            </div>

        </div>

        <div
            id="microfinance_options"
            style="display:none;">

            <div class="form-grid form-grid--2">

                <div class="form-group">

                    <label
                        class="form-label"
                        for="collection_day">

                        Collection Day

                    </label>

                    <select
                        class="form-control"
                        id="collection_day"
                        name="collection_day">

                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>

                    </select>

                </div>

                <div class="form-group">

                    <label
                        class="form-label"
                        for="grace_period">

                        Grace Period (Days)

                    </label>

                    <input
                        class="form-control"
                        type="number"
                        id="grace_period"
                        name="grace_period"
                        value="0">

                </div>

            </div>

        </div>

    </div>

</div>