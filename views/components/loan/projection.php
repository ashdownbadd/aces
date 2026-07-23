<div class="card">

    <div class="card__header">

        <div>

            <h2 class="card__title">

                <i class="fas fa-table"></i>

                Repayment Schedule

            </h2>

            <p class="card__subtitle">

                Automatically generated repayment schedule.

            </p>

        </div>

    </div>

    <div class="card__body">

        <div class="table table--sticky table--compact">

            <div class="table__scroll table__scroll--projection">

                <table>

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Due Date</th>

                            <th class="table__number">Principal</th>

                            <th class="table__number">Interest</th>

                            <th class="table__number">Payment</th>

                            <th class="table__number">Balance</th>

                        </tr>

                    </thead>

                    <tbody id="loan_preview_body">

                        <tr>

                            <td
                                colspan="6"
                                class="table__empty">

                                <div class="table__empty-icon">

                                    <i class="fas fa-table"></i>

                                </div>

                                <div class="table__empty-title">

                                    No Projection Yet

                                </div>

                                <div class="table__empty-description">

                                    Fill out the loan information to generate the repayment schedule.

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card__footer">

        <?php c('loan/actions'); ?>

    </div>

</div>