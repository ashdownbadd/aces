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

  Loan.renderEmptySchedule = function () {
    const tbody = Loan.$("loan_preview_body");

    if (!tbody) {
      return;
    }

    tbody.innerHTML = `
            <tr>
                <td colspan="6" class="table__empty">
                    Complete the loan details above to generate a live repayment schedule.
                </td>
            </tr>
        `;
  };

  /* ==========================================================
   * Amortization Table
   * ==========================================================
   */

  Loan.renderSchedule = function (schedule) {
    const tbody = Loan.$("loan_preview_body");

    if (!tbody) {
      return;
    }

    if (!schedule.length) {
      Loan.renderEmptySchedule();

      return;
    }

    tbody.innerHTML = "";

    const rows = schedule
      .map(
        (row) => `
            <tr>
                <td>${row.paymentNo}</td>
                <td>${Loan.formatDate(row.dueDate)}</td>
                <td>${Loan.peso(row.principal)}</td>
                <td>${Loan.peso(row.interest)}</td>
                <td>${Loan.peso(row.payment)}</td>
                <td>${Loan.peso(row.balance)}</td>
            </tr>
        `,
      )
      .join("");

    tbody.innerHTML = rows;
  };
})();
