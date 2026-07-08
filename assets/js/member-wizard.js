document.addEventListener("DOMContentLoaded", () => {
  const steps = [...document.querySelectorAll(".member-step")];

  const previous = document.getElementById("wizardPrevious");
  const next = document.getElementById("wizardNext");
  const submit = document.getElementById("wizardSubmit");

  const progressBar = document.getElementById("wizardProgressBar");
  const stepNumber = document.getElementById("wizardStepNumber");
  const stepTitle = document.getElementById("wizardStepTitle");

  const titles = [
    "Personal Information",
    "Contact Information",
    "Address",
    "Review",
  ];

  if (!steps.length) return;

  let current = 0;

  function value(name) {
    const field = document.querySelector(`[name="${name}"]`);

    if (!field) return "-";

    return field.value.trim() || "-";
  }

  function updateReview() {
    const reviewName = document.getElementById("reviewName");

    if (!reviewName) return;

    document.getElementById("reviewName").textContent =
      `${value("first_name")} ${value("middle_name")} ${value("last_name")}`
        .replace(/\s+/g, " ")
        .trim();

    document.getElementById("reviewBirth").textContent = value("date_of_birth");

    document.getElementById("reviewSex").textContent = value("sex");

    document.getElementById("reviewMarital").textContent =
      value("marital_status");

    document.getElementById("reviewEmail").textContent = value("email");

    document.getElementById("reviewPhone1").textContent = value("phone_no_1");

    document.getElementById("reviewPhone2").textContent = value("phone_no_2");

    document.getElementById("reviewAddress").textContent = [
      value("house_number"),
      value("street"),
      value("barangay"),
      value("town_city"),
      value("province"),
      value("region"),
    ]
      .filter((item) => item !== "-")
      .join(", ");
  }

  function validateCurrentStep() {
    const activeStep = steps[current];

    const requiredFields = activeStep.querySelectorAll("[required]");

    let valid = true;

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        field.classList.add("form-control--error");
        if (valid) {
          field.focus();
        }

        valid = false;
      } else {
        field.classList.add("form-control--error");
      }
    });

    return valid;
  }

  function updateWizard() {
    steps.forEach((step, index) => {
      step.classList.toggle("is-active", index === current);
    });

    stepNumber.textContent = current + 1;

    stepTitle.textContent = titles[current];

    progressBar.style.width = `${((current + 1) / steps.length) * 100}%`;

    previous.style.display = current === 0 ? "none" : "inline-flex";

    next.style.display = current === steps.length - 1 ? "none" : "inline-flex";

    submit.style.display =
      current === steps.length - 1 ? "inline-flex" : "none";

    updateReview();

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  }

  next.addEventListener("click", () => {
    if (!validateCurrentStep()) {
      return;
    }

    if (current < steps.length - 1) {
      current++;

      updateWizard();
    }
  });

  previous.addEventListener("click", () => {
    if (current > 0) {
      current--;

      updateWizard();
    }
  });

  document
    .querySelectorAll("#memberWizard input, #memberWizard select")
    .forEach((field) => {
      field.addEventListener("input", () => {
        field.classList.add("form-control--error");

        updateReview();
      });

      field.addEventListener("change", () => {
        field.classList.add("form-control--error");

        updateReview();
      });
    });

  updateWizard();
});
