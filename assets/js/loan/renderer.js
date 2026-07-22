/**
 * ==========================================================
 * ACES Cooperative
 * Loan Renderer
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ==========================================================
   * Loan Summary
   * ==========================================================
   */

  Loan.renderSummary = () => {
    const loan = Loan.state.loan;

    Loan.$("#lbl_principal").textContent = Loan.peso(loan.principal);

    Loan.$("#lbl_interest").textContent = Loan.peso(loan.totalInterest);

    Loan.$("#lbl_total").textContent = Loan.peso(loan.totalRepayment);

    Loan.$("#lbl_payments").textContent = loan.numberOfPayments;
  };

  /* ==========================================================
   * Estimated Payment
   * ==========================================================
   */

  Loan.renderEstimatedPayment = () => {
    const loan = Loan.state.loan;

    Loan.$("#lbl_estimated_payment").textContent = Loan.peso(
      loan.paymentAmount,
    );

    Loan.$("#lbl_payment_frequency").textContent =
      "Per " + loan.paymentFrequency;
  };

  /* ==========================================================
   * Empty Schedule
   * ==========================================================
   */

  Loan.renderEmptySchedule = () => {
    Loan.$("#loan_preview_body").innerHTML = `
        <tr>
            <td colspan="6" class="table__empty">

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
    `;
  };

  /* ==========================================================
   * Amortization Table
   * ==========================================================
   */

  Loan.renderSchedule = () => {
    const rows = Loan.state.schedule
      .map(
        (payment, index) => `

        <tr>

            <td>${index + 1}</td>

            <td>${payment.payment_date}</td>

            <td class="table__number">${Loan.peso(payment.principal)}</td>

            <td class="table__number">${Loan.peso(payment.interest)}</td>

            <td class="table__number">${Loan.peso(payment.payment)}</td>

            <td class="table__number">${Loan.peso(payment.balance)}</td>

        </tr>

    `,
      )
      .join("");

    Loan.$("#loan_preview_body").innerHTML = rows;
  };
})();
