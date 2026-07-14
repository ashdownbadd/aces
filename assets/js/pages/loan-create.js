/**
 * ==========================================================
 * Loan Creation
 * Integrated Cooperative System
 * ==========================================================
 */

let isInterestManuallyEdited = false;

/* ==========================================================
 * Helpers
 * ========================================================== */

const $ = (id) => document.getElementById(id);

const show = (element) => {
  if (!element) return;

  element.classList.remove("loan-hidden");
};

const hide = (element) => {
  if (!element) return;

  element.classList.add("loan-hidden");
};

const peso = (value) => `₱${value.toFixed(2)}`;

/* ==========================================================
 * Loan Type
 * ========================================================== */

function handleLoanTypeChange() {
  const loanType = $("loan_type").value;

  const rateInput = $("interest_rate");

  const frequencyPanel = $("frequency_panel");

  const amortization = $("amortization_type");

  if (loanType === "Micro-Finance Loan") {
    rateInput.value = "5.00";

    isInterestManuallyEdited = false;

    show(frequencyPanel);

    amortization.value = "Straight-line";

    amortization.disabled = true;

    amortization.removeAttribute("name");

    if (!$("hidden_amort_type")) {
      const input = document.createElement("input");

      input.type = "hidden";

      input.id = "hidden_amort_type";

      input.name = "amortization_type";

      input.value = "Straight-line";

      amortization.parentNode.appendChild(input);
    }
  } else {
    if (!isInterestManuallyEdited) {
      rateInput.value = "2.00";
    }

    hide(frequencyPanel);

    amortization.disabled = false;

    amortization.name = "amortization_type";

    const hidden = $("hidden_amort_type");

    if (hidden) {
      hidden.remove();
    }
  }

  handleAmortTypeChange();

  calculateLiveDeductions();
}

/* ==========================================================
 * Interest
 * ========================================================== */

function flagManualInterest() {
  isInterestManuallyEdited = true;
}

/* ==========================================================
 * Amortization
 * ========================================================== */

function handleAmortTypeChange() {
  const panel = $("manual_payment_panel");

  if ($("amortization_type").value === "Manual") {
    show(panel);
  } else {
    hide(panel);
  }
}

/* ==========================================================
 * Collateral
 * ========================================================== */

function handleCollateralChange() {
  const panel = $("real_property_panel");

  if ($("collateral").value === "Real Property") {
    show(panel);
  } else {
    hide(panel);
  }
}

/* ==========================================================
 * Loan Projection
 * ========================================================== */

function calculateLiveDeductions() {
  const principal = parseFloat($("principal").value) || 0;

  const terms = parseInt($("terms").value) || 0;

  if (principal <= 0 || terms <= 0) {
    $("lbl_processing").textContent = "₱0.00";

    $("lbl_insurance").textContent = "₱0.00";

    $("lbl_notarial").textContent = "₱0.00";

    $("lbl_net").textContent = "₱0.00";

    return;
  }

  const processing = principal * 0.02;

  const insurance = (principal / 1000) * 1.2 * terms;

  const notarial = 400;

  const net = principal - processing - insurance - notarial;

  $("lbl_processing").textContent = peso(processing);

  $("lbl_insurance").textContent = peso(insurance);

  $("lbl_notarial").textContent = peso(notarial);

  $("lbl_net").textContent = peso(net);
}

/* ==========================================================
 * Initialize
 * ========================================================== */

document.addEventListener("DOMContentLoaded", () => {
  handleLoanTypeChange();

  handleCollateralChange();

  handleAmortTypeChange();

  calculateLiveDeductions();
});
