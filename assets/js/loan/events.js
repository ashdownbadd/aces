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

  Loan.handleLoanType = function () {
    const loanType = Loan.$("loan_type");

    const rateInput = Loan.$("interest_rate");

    const frequencyPanel = Loan.$("frequency_panel");

    const amortization = Loan.$("amortization_type");

    if (!loanType || !rateInput || !frequencyPanel || !amortization) {
      return;
    }

    if (loanType.value === "Micro-Finance Loan") {
      rateInput.value = "5.00";

      isInterestManuallyEdited = false;

      Loan.show(frequencyPanel);

      amortization.value = "Straight-line";

      amortization.disabled = true;

      amortization.removeAttribute("name");

      let hidden = Loan.$("hidden_amort_type");

      if (!hidden) {
        hidden = document.createElement("input");

        hidden.type = "hidden";

        hidden.id = "hidden_amort_type";

        hidden.name = "amortization_type";

        amortization.parentNode.appendChild(hidden);
      }

      hidden.value = "Straight-line";
    } else {
      if (!isInterestManuallyEdited) {
        rateInput.value = "2.00";
      }

      Loan.hide(frequencyPanel);

      amortization.disabled = false;

      amortization.name = "amortization_type";

      const hidden = Loan.$("hidden_amort_type");

      if (hidden) {
        hidden.remove();
      }
    }

    Loan.handleAmortization();

    Loan.calculate();
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

  Loan.handleAmortization = function () {
    const panel = Loan.$("manual_payment_panel");

    const amortization = Loan.$("amortization_type");

    if (!panel || !amortization) {
      return;
    }

    if (amortization.value === "Manual") {
      Loan.show(panel);
    } else {
      Loan.hide(panel);
    }
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

    Loan.handleCollateral();

    Loan.handleAmortization();

    Loan.attachEvents();

    Loan.calculate();
  };
})();
