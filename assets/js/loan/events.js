/**
 * ==========================================================
 * ACES Cooperative
 * Loan Events
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ======================================================
   * Main Refresh Pipeline
   * ======================================================
   */

  Loan.refresh = function () {
    let loan = Loan.collect();

    loan = Loan.calculateSummary(loan);

    Loan.state.loan = loan;

    Loan.state.deductions = Loan.calculateDeductions(loan);

    Loan.state.schedule = Loan.generateSchedule(loan);

    Loan.render();
  };

  /* ======================================================
   * Loan Type
   * ======================================================
   */

  Loan.handleLoanType = function () {
    const type = Loan.value("loan_type");

    const micro = Loan.$("microfinance_options");

    if (micro) {
      micro.style.display = type === "Micro-Finance Loan" ? "block" : "none";
    }

    Loan.refresh();
  };

  /* ======================================================
   * Collateral
   * ======================================================
   */

  Loan.handleCollateral = function () {
    const collateral = Loan.value("collateral");

    const fields = Loan.$("real_property_fields");

    if (fields) {
      fields.style.display = collateral === "Real Property" ? "block" : "none";
    }

    Loan.refresh();
  };

  /* ======================================================
   * Amortization
   * ======================================================
   */

  Loan.handleAmortization = function () {
    const type = Loan.value("amortization_type");

    const manual = Loan.$("manual_interest_container");

    if (manual) {
      manual.style.display = type === "Manual" ? "block" : "none";
    }

    Loan.refresh();
  };

  /* ======================================================
   * Member Information
   * ======================================================
   */

  Loan.updateMember = function () {
    const select = Loan.$("member_id");

    if (!select) return;

    const option = select.selectedOptions[0];

    Loan.text(
      "loanMemberName",

      option?.dataset.name || "No member selected",
    );

    Loan.text(
      "loanMemberNumber",

      option?.dataset.number || "Select a borrower",
    );
  };

  /* ======================================================
   * Attach Events
   * ======================================================
   */

  Loan.attachEvents = function () {
    [
      "principal",

      "interest_rate",

      "terms",

      "start_date",

      "payment_frequency",

      "manual_payment",
    ].forEach((id) => {
      const element = Loan.$(id);

      if (!element) return;

      element.addEventListener(
        "input",

        Loan.refresh,
      );

      element.addEventListener(
        "change",

        Loan.refresh,
      );
    });

    Loan.$("loan_type")?.addEventListener(
      "change",

      Loan.handleLoanType,
    );

    Loan.$("collateral")?.addEventListener(
      "change",

      Loan.handleCollateral,
    );

    Loan.$("amortization_type")?.addEventListener(
      "change",

      Loan.handleAmortization,
    );

    Loan.$("member_id")?.addEventListener(
      "change",

      () => {
        Loan.updateMember();

        Loan.refresh();
      },
    );
  };

  /* ======================================================
   * Init
   * ======================================================
   */

  Loan.init = function () {
    Loan.attachEvents();

    Loan.handleLoanType();

    Loan.handleCollateral();

    Loan.handleAmortization();

    Loan.updateMember();

    Loan.refresh();
  };
})();
