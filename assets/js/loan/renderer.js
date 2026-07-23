/**
 * ==========================================================
 * ACES Cooperative
 * Loan Renderer
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ======================================================
   * Render Everything
   * ====================================================== */

  Loan.render = function () {
    Loan.renderSummary();

    Loan.renderEstimatedPayment();

    Loan.renderSchedule();
  };

  /* ======================================================
   * Loan Summary
   * ====================================================== */

  Loan.renderSummary = function () {
    const loan = Loan.state.loan;

    Loan.text("lbl_principal", Loan.money(loan.principal));

    Loan.text("lbl_interest", Loan.money(loan.totalInterest));

    Loan.text("lbl_total", Loan.money(loan.totalRepayment));

    Loan.text("lbl_payments", loan.numberOfPayments);
  };

  /* ======================================================
   * Estimated Payment
   * ====================================================== */

  Loan.renderEstimatedPayment = function () {
    const loan = Loan.state.loan;

    Loan.text("lbl_estimated_payment", Loan.money(loan.paymentAmount));

    let label = "Monthly Payment";

    switch (loan.paymentFrequency) {
      case "Weekly":
        label = "Weekly Payment";

        break;

      case "Bi-Monthly":
        label = "Bi-Monthly Payment";

        break;
    }

    Loan.text("lbl_payment_frequency", label);
  };

  /* ======================================================
   * Empty Schedule
   * ====================================================== */

  Loan.renderEmptySchedule = function () {
    Loan.html(
      "loan_preview_body",

      `
            <tr>

                <td colspan="6" class="table__empty">

                    <div class="table__empty-icon">

                        <i class="fas fa-calendar"></i>

                    </div>

                    <div class="table__empty-title">

                        No Projection Yet

                    </div>

                    <div class="table__empty-description">

                        Fill out the loan information to generate the repayment schedule.

                    </div>

                </td>

            </tr>
            `,
    );
  };

  /* ======================================================
   * Schedule
   * ====================================================== */

  Loan.renderSchedule = function () {
    const schedule = Loan.state.schedule;

    if (!schedule.length) {
      Loan.renderEmptySchedule();

      return;
    }

    let html = "";

    schedule.forEach((row) => {
      html += `

            <tr>

                <td>

                    ${row.paymentNo}

                </td>

                <td>

                    ${Loan.formatDate(row.dueDate)}

                </td>

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

                    ${Loan.money(row.balance)}

                </td>

            </tr>

            `;
    });

    Loan.html(
      "loan_preview_body",

      html,
    );
  };
})();
