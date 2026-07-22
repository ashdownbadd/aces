/**
 * ==========================================================
 * ACES Cooperative
 * Loan Events
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  let isInterestManuallyEdited = false;

  /* ==========================================================
   * Calculate
   * ==========================================================
   */

  Loan.calculate = function () {
    Loan.state.loan = Loan.getLoanInputs();

    Loan.state.deductions = Loan.calculateDeductions(Loan.state.loan);

    Loan.state.schedule = Loan.generateSchedule(Loan.state.loan);

    Loan.renderSummary(Loan.state.deductions);

    Loan.renderEstimatedPayment(Loan.state.schedule);

    Loan.renderSchedule(Loan.state.schedule);
  };

  /* ==========================================================
   * Loan Type
   * ==========================================================
   */

  Loan.handleLoanType = () => {
    const type = Loan.$("#loan_type").value;

    Loan.$("#microfinance_options").style.display =
      type === "Micro-Finance Loan" ? "block" : "none";

    Loan.handleCollateral();
  };

  /* ==========================================================
   * Interest
   * ==========================================================
   */

  Loan.flagManualInterest = function () {
    isInterestManuallyEdited = true;
  };

  /* ==========================================================
   * Amortization
   * ==========================================================
   */

  Loan.handleAmortization = () => {
    const type = Loan.$("#amortization_type").value;

    Loan.$("#manual_interest_container").style.display =
      type === "Manual" ? "block" : "none";
  };

  /* ==========================================================
   * Collateral
   * ==========================================================
   */

  Loan.handleCollateral = function () {
    const panel = Loan.$("real_property_panel");

    const collateral = Loan.$("collateral");

    if (!panel || !collateral) {
      return;
    }

    if (collateral.value === "Real Property") {
      Loan.show(panel);
    } else {
      Loan.hide(panel);
    }
  };

  /* ==========================================================
   * Events
   * ==========================================================
   */

  Loan.attachEvents = function () {
    const liveInputs = [
      "principal",

      "interest_rate",

      "terms",

      "start_date",

      "payment_frequency",

      "manual_payment",
    ];

    liveInputs.forEach((id) => {
      const element = Loan.$(id);

      if (!element) return;

      element.addEventListener("input", Loan.calculate);

      element.addEventListener("change", Loan.calculate);
    });

    Loan.$("loan_type")?.addEventListener("change", Loan.handleLoanType);

    Loan.$("collateral")?.addEventListener("change", () => {
      Loan.handleCollateral();

      Loan.calculate();
    });

    Loan.$("amortization_type")?.addEventListener("change", () => {
      Loan.handleAmortization();

      Loan.calculate();
    });

    Loan.$("interest_rate")?.addEventListener("input", () => {
      Loan.flagManualInterest();

      Loan.calculate();
    });
  };

  /* ==========================================================
   * Init
   * ==========================================================
   */

  Loan.init = function () {
    Loan.handleLoanType();

    Loan.handleAmortization();

    Loan.handleCollateral();

    Loan.attachEvents();

    Loan.calculate();
  };
})();
