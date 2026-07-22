/**
 * ==========================================================
 * ACES Cooperative
 * Loan Calculator
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ==========================================================
   * Loan Inputs
   * ==========================================================
   */

  Loan.getLoanInputs = function () {
    return {
      memberId: Loan.$("member_id")?.value || "",

      loanType: Loan.$("loan_type")?.value || "",

      principal: parseFloat(Loan.$("principal")?.value) || 0,

      interestRate: parseFloat(Loan.$("interest_rate")?.value) || 0,

      terms: parseInt(Loan.$("terms")?.value) || 0,

      startDate: Loan.$("start_date")?.value || "",

      paymentFrequency: Loan.$("payment_frequency")?.value || "Monthly",

      amortizationType: Loan.$("amortization_type")?.value || "Straight-line",

      collateral: Loan.$("collateral")?.value || "",

      manualPayment: parseFloat(Loan.$("manual_payment")?.value) || 0,
    };
  };

  /* ==========================================================
   * Loan Deductions
   * ==========================================================
   */

  Loan.calculateDeductions = function (loan) {
    if (loan.principal <= 0 || loan.terms <= 0) {
      return {
        processing: 0,
        insurance: 0,
        notarial: 400,
        net: 0,
      };
    }

    const processing = loan.principal * 0.02;

    const insurance = (loan.principal / 1000) * 1.2 * loan.terms;

    const notarial = 400;

    const net = loan.principal - processing - insurance - notarial;

    return {
      processing,

      insurance,

      notarial,

      net,
    };
  };
})();
