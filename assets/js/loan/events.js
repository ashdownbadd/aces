/**
 * ==========================================================
 * ACES Cooperative
 * Loan Events
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ======================================================
   * Initialize Events
   * ====================================================== */

  Loan.initializeEvents = function () {
    const fields = [
      "member_id",
      "loan_type",
      "collateral",
      "principal",
      "interest_rate",
      "terms",
      "start_date",
      "payment_frequency",
      "amortization_type",
      "manual_interest",
    ];

    fields.forEach((id) => {
      const element = Loan.$(id);

      if (!element) {
        return;
      }

      const handler = () => {
        if (id === "amortization_type") {
          Loan.toggleManualInterest();
        }

        if (id === "member_id") {
          Loan.updateBorrowerPreview();
        }

        Loan.refresh();
      };

      element.addEventListener("input", handler);
      element.addEventListener("change", handler);
    });

    Loan.toggleManualInterest();

    Loan.updateBorrowerPreview();

    Loan.refresh();
  };

  /* ======================================================
   * Manual Interest Visibility
   * ====================================================== */

  Loan.toggleManualInterest = function () {
    const wrapper = Loan.$("manual_interest_container");

    if (!wrapper) {
      return;
    }

    const isManual = Loan.value("amortization_type") === "manual";

    if (isManual) {
      Loan.show(wrapper);
    } else {
      Loan.hide(wrapper);
    }
  };

  /* ======================================================
   * Borrower Preview
   * ====================================================== */

  Loan.updateBorrowerPreview = function () {
    const select = Loan.$("member_id");

    if (!select) {
      return;
    }

    const option = select.options[select.selectedIndex];

    const name = Loan.$("loanMemberName");

    const number = Loan.$("loanMemberNumber");

    if (!option || !option.value) {
      name.textContent = "No member selected";

      number.textContent = "Select a borrower to continue.";

      return;
    }

    name.textContent = option.dataset.name;

    number.textContent = option.dataset.number;
  };
})();
