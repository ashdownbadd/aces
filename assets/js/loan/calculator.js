/**
 * ==========================================================
 * ACES Cooperative
 * Loan Calculator
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ======================================================
   * Collect Form Data
   * ====================================================== */

  Loan.collect = function () {
    return {
      memberId: Loan.value("member_id"),

      loanType: Loan.value("loan_type"),

      collateral: Loan.value("collateral"),

      principal: Loan.number("principal"),

      interestRate: Loan.number("interest_rate"),

      terms: Loan.integer("terms"),

      startDate: Loan.value("start_date"),

      paymentFrequency: Loan.value("payment_frequency"),

      amortizationType: Loan.value("amortization_type"),

      manualInterest: Loan.number("manual_interest"),
    };
  };

  /* ======================================================
   * Loan Refresh
   * ====================================================== */

  Loan.isReady = function (loan) {
    return (
      loan.memberId &&
      loan.loanType &&
      loan.paymentFrequency &&
      loan.amortizationType &&
      loan.principal > 0 &&
      loan.interestRate > 0 &&
      loan.terms > 0 &&
      loan.startDate
    );
  };

  Loan.refresh = function () {
    const loan = Loan.collect();

    Loan.state.loan = loan;

    if (!Loan.isReady(loan)) {
      Loan.state.schedule = [];

      loan.summary = Loan.buildSummary([]);

      Loan.render();

      return;
    }

    Loan.state.schedule = Loan.generateSchedule(loan);

    loan.summary = Loan.buildSummary(Loan.state.schedule);

    Loan.state.deductions = Loan.calculateDeductions(loan);

    Loan.render();
  };

  /* ======================================================
   * Loan Deductions
   * ====================================================== */

  Loan.calculateDeductions = function (loan) {
    const processing = loan.principal * 0.02;

    const insurance = (loan.principal / 1000) * 1.2 * loan.terms;

    const notarial = 400;

    return {
      processing: Loan.round(processing),

      insurance: Loan.round(insurance),

      notarial,

      net: Loan.round(loan.principal - processing - insurance - notarial),
    };
  };
})();
