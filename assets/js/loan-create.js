let isInterestManuallyEdited = false;

function handleLoanTypeChange() {
  const loanType = document.getElementById("loan_type").value;
  const rateInput = document.getElementById("interest_rate");
  const freqPanel = document.getElementById("frequency_panel");
  const amortSelect = document.getElementById("amortization_type");

  if (loanType === "Micro-Finance Loan") {
    rateInput.value = "5.00";
    isInterestManuallyEdited = false;
    freqPanel.style.display = "block";

    // Lock select option
    amortSelect.value = "Straight-line";
    amortSelect.style.backgroundColor = "#eee";
    amortSelect.disabled = true;
    amortSelect.removeAttribute("name");

    // Hidden fallback input
    if (!document.getElementById("hidden_amort_type")) {
      const hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.id = "hidden_amort_type";
      hiddenInput.name = "amortization_type";
      hiddenInput.value = "Straight-line";
      amortSelect.parentNode.appendChild(hiddenInput);
    }
  } else {
    if (!isInterestManuallyEdited) {
      rateInput.value = "2.00";
    }

    freqPanel.style.display = "none";

    amortSelect.disabled = false;
    amortSelect.style.backgroundColor = "#fff";
    amortSelect.setAttribute("name", "amortization_type");

    const hiddenInput = document.getElementById("hidden_amort_type");

    if (hiddenInput) {
      hiddenInput.remove();
    }
  }

  handleAmortTypeChange();
  calculateLiveDeductions();
}

function flagManualInterest() {
  isInterestManuallyEdited = true;
}

function handleAmortTypeChange() {
  const type = document.getElementById("amortization_type").value;
  const manualPanel = document.getElementById("manual_payment_panel");

  if (manualPanel) {
    manualPanel.style.display = type === "Manual" ? "block" : "none";
  }
}

function handleCollateralChange() {
  const classVal = document.getElementById("collateral").value;
  const propertyPanel = document.getElementById("real_property_panel");

  if (propertyPanel) {
    propertyPanel.style.display =
      classVal === "Real Property" ? "block" : "none";
  }
}

function calculateLiveDeductions() {
  const principal = parseFloat(document.getElementById("principal").value) || 0;

  const terms = parseInt(document.getElementById("terms").value) || 0;

  const processingLabel = document.getElementById("lbl_processing");

  const insuranceLabel = document.getElementById("lbl_insurance");

  const notarialLabel = document.getElementById("lbl_notarial");

  const netLabel = document.getElementById("lbl_net");

  if (principal > 0 && terms > 0) {
    const processing = principal * 0.02;

    const insurance = (principal / 1000) * 1.2 * terms;

    const notarial = 400.0;

    const net = principal - processing - insurance - notarial;

    processingLabel.textContent = `₱${processing.toFixed(2)}`;

    insuranceLabel.textContent = `₱${insurance.toFixed(2)}`;

    notarialLabel.textContent = `₱${notarial.toFixed(2)}`;

    netLabel.textContent = `₱${net.toFixed(2)}`;
  } else {
    processingLabel.textContent = "₱0.00";
    insuranceLabel.textContent = "₱0.00";
    notarialLabel.textContent = "₱0.00";
    netLabel.textContent = "₱0.00";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  handleLoanTypeChange();
});
