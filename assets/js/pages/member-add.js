document.addEventListener("DOMContentLoaded", () => {
  const wizard = document.getElementById("memberWizard");

  if (!wizard) {
    return;
  }

  const steps = [...wizard.querySelectorAll(".member-step")];

  if (!steps.length) {
    return;
  }

  const previousButton = document.getElementById("wizardPrevious");
  const nextButton = document.getElementById("wizardNext");
  const submitButton = document.getElementById("wizardSubmit");

  const progressBar = document.getElementById("wizardProgressBar");
  const stepNumber = document.getElementById("wizardStepNumber");
  const stepTitle = document.getElementById("wizardStepTitle");

  const titles = [
    "Personal Information",
    "Contact Information",
    "Address Information",
    "Employment Information",
    "Educational Background",
    "Beneficiaries",
    "Review & Submit",
  ];

  let currentStep = 0;

  function getValue(name) {
    const field = wizard.querySelector(`[name="${name}"]`);

    if (!field) {
      return "-";
    }

    const value = field.value.trim();

    return value === "" ? "-" : value;
  }

  function updateReview() {
    const reviewName = document.getElementById("reviewName");

    if (!reviewName) {
      return;
    }

    reviewName.textContent = [
      getValue("first_name"),
      getValue("middle_name"),
      getValue("last_name"),
    ]
      .filter((value) => value !== "-")
      .join(" ");

    document.getElementById("reviewBirth").textContent =
      getValue("date_of_birth");
    document.getElementById("reviewSex").textContent = getValue("sex");
    document.getElementById("reviewMarital").textContent =
      getValue("marital_status");
    document.getElementById("reviewEmail").textContent = getValue("email");
    document.getElementById("reviewPhone1").textContent =
      getValue("phone_no_1");
    document.getElementById("reviewPhone2").textContent =
      getValue("phone_no_2");

    document.getElementById("reviewAddress").textContent = [
      getValue("house_number"),
      getValue("street"),
      getValue("barangay"),
      getValue("town_city"),
      getValue("province"),
      getValue("region"),
    ]
      .filter((value) => value !== "-")
      .join(", ");
  }

  function validateCurrentStep() {
    let valid = true;

    const requiredFields = steps[currentStep].querySelectorAll("[required]");

    requiredFields.forEach((field) => {
      if (field.value.trim() === "") {
        field.classList.add("form-control--error");

        if (valid) {
          field.focus();
        }

        valid = false;
      } else {
        field.classList.remove("form-control--error");
      }
    });

    return valid;
  }

  function updateProgress() {
    const percentage = ((currentStep + 1) / steps.length) * 100;

    progressBar.style.width = `${percentage}%`;
  }

  function updateButtons() {
    previousButton.style.display = currentStep === 0 ? "none" : "inline-flex";

    nextButton.style.display =
      currentStep === steps.length - 1 ? "none" : "inline-flex";

    submitButton.style.display =
      currentStep === steps.length - 1 ? "inline-flex" : "none";
  }

  function updateHeader() {
    stepNumber.textContent = currentStep + 1;
    stepTitle.textContent = titles[currentStep] ?? "";
  }

  function updateSteps() {
    steps.forEach((step, index) => {
      step.classList.toggle("is-active", index === currentStep);
    });
  }

  function updateWizard() {
    updateSteps();
    updateHeader();
    updateProgress();
    updateButtons();
    updateReview();

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  }

  nextButton.addEventListener("click", () => {
    if (!validateCurrentStep()) {
      return;
    }

    if (currentStep < steps.length - 1) {
      currentStep++;
      updateWizard();
    }
  });

  previousButton.addEventListener("click", () => {
    if (currentStep > 0) {
      currentStep--;
      updateWizard();
    }
  });

  wizard.querySelectorAll("input, select, textarea").forEach((field) => {
    function clearError() {
      field.classList.remove("form-control--error");
      updateReview();
    }

    field.addEventListener("input", clearError);
    field.addEventListener("change", clearError);
  });

  updateWizard();
});
