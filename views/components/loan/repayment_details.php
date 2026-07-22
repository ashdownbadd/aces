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
                    required>

                    <option value="Straight-line">

                        Straight-line

                    </option>

                    <option value="Diminishing Balance">

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