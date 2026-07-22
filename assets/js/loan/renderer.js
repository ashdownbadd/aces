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

  Loan.renderSummary = function (deductions) {
    Loan.$("lbl_processing").textContent = Loan.peso(deductions.processing);

    Loan.$("lbl_insurance").textContent = Loan.peso(deductions.insurance);

    Loan.$("lbl_notarial").textContent = Loan.peso(deductions.notarial);

    Loan.$("lbl_net").textContent = Loan.peso(deductions.net);
  };

  /* ==========================================================
   * Estimated Payment
   * ==========================================================
   */

  Loan.renderEstimatedPayment = function (schedule) {
    const paymentLabel = Loan.$("lbl_estimated_payment");

    const frequencyLabel = Loan.$("lbl_payment_frequency");

    if (!paymentLabel || !frequencyLabel) {
      return;
    }

    if (!schedule.length) {
      paymentLabel.textContent = Loan.peso(0);

      frequencyLabel.textContent = "Per Payment";

      return;
    }

    paymentLabel.textContent = Loan.peso(schedule[0].payment);

    frequencyLabel.textContent = "Per Payment";
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
