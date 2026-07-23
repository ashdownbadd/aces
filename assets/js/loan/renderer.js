/**
 * ==========================================================
 * ACES Cooperative
 * Loan Renderer
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  Loan.render = function () {
    Loan.renderSummary();

    Loan.renderProjection();
  };

  /* ======================================================
   * Summary
   * ====================================================== */

  Loan.renderSummary = function () {
    const loan = Loan.state.loan;

    if (!loan || !loan.summary) {
      return;
    }

    Loan.setText("lbl_principal", Loan.money(loan.principal));

    Loan.setText("lbl_interest", Loan.money(loan.summary.totalInterest));

    Loan.setText("lbl_total", Loan.money(loan.summary.totalRepayment));

    Loan.setText("lbl_payments", loan.summary.paymentCount);

    Loan.setText(
      "lbl_estimated_payment",
      Loan.money(loan.summary.firstPayment),
    );

    Loan.setText(
      "lbl_payment_frequency",
      Loan.getPaymentFrequencyLabel(loan.paymentFrequency),
    );
  };

  /* ======================================================
   * Projection Table
   * ====================================================== */

  Loan.renderProjection = function () {
    const tbody = Loan.$("loan_preview_body");

    if (!tbody) {
      return;
    }

    const schedule = Loan.state.schedule;

    if (!schedule.length) {
      tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="table__empty">
                        No Projection Yet
                    </td>
                </tr>
            `;

      return;
    }

    tbody.innerHTML = schedule
      .map(
        (row) => `

            <tr>

                <td>${row.paymentNo}</td>

<td>${Loan.date(row.dueDate)}</td>

<td class="table__number">
    ${Loan.money(row.principal)}
</td>

<td class="table__number">
    ${Loan.money(row.interest)}
</td>

<td class="table__number">
    ${Loan.money(row.payment)}
</td>

<td class="table__number">
    ${Loan.money(row.endingBalance)}
</td>

            </tr>

        `,
      )
      .join("");
  };

  /* ======================================================
   * Frequency Label
   * ====================================================== */

  Loan.getPaymentFrequencyLabel = function (frequency) {
    switch (frequency) {
      case "monthly":
        return "Monthly Payment";

      case "semi_monthly":
        return "Semi-Monthly Payment";

      case "bi_weekly":
        return "Bi-Weekly Payment";

      case "weekly":
        return "Weekly Payment";

      case "daily":
        return "Daily Payment";

      default:
        return "";
    }
  };
})();
