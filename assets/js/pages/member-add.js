document.addEventListener("DOMContentLoaded", () => {
  initializeWizard();

  initializeEmployment();

  initializeEducation();

  initializeBeneficiaries();

  initializeReview();
});

/* ==========================================================
   MEMBER WIZARD STATE
========================================================== */

const MemberWizard = {
  currentStep: 0,

  titles: [
    "Personal Information",
    "Contact Information",
    "Address Information",
    "Employment Information",
    "Educational Background",
    "Beneficiaries",
    "Review & Submit",
  ],

  wizard: null,

  steps: [],

  previousButton: null,

  nextButton: null,

  submitButton: null,

  stepNumber: null,

  stepTitle: null,

  goTo: null,

  refresh: null,
};

/* ==========================================================
   WIZARD
========================================================== */

function initializeWizard() {
  MemberWizard.wizard = document.getElementById("memberWizard");

  const wizard = MemberWizard.wizard;

  if (!wizard) {
    return;
  }

  MemberWizard.steps = [...wizard.querySelectorAll(".member-step")];

  if (!MemberWizard.steps.length) {
    return;
  }

  MemberWizard.previousButton = document.getElementById("wizardPrevious");
  MemberWizard.nextButton = document.getElementById("wizardNext");
  MemberWizard.submitButton = document.getElementById("wizardSubmit");
  MemberWizard.stepNumber = document.getElementById("wizardStepNumber");
  MemberWizard.stepTitle = document.getElementById("wizardStepTitle");
  MemberWizard.currentStep = 0;

  /* ======================================================
       HELPERS
    ====================================================== */

  function getValue(name) {
    const field = wizard.querySelector(`[name="${name}"]`);

    if (!field) {
      return "-";
    }

    const value = field.value.trim();

    return value === "" ? "-" : value;
  }

  /* ======================================================
   FORMATTERS
====================================================== */

  function formatCurrency(value) {
    const amount = parseFloat(value);

    if (Number.isNaN(amount)) {
      return "-";
    }

    return new Intl.NumberFormat("en-PH", {
      style: "currency",
      currency: "PHP",
    }).format(amount);
  }

  function formatDate(value) {
    if (!value || value === "-") {
      return "-";
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
      return value;
    }

    return date.toLocaleDateString("en-PH", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  }

  function formatPhone(value) {
    if (!value || value === "-") {
      return "-";
    }

    return value;
  }

  function formatAddress(...parts) {
    return parts.filter((part) => part && part !== "-").join(", ");
  }

  function validateCurrentStep() {
    return Validation.validateStep(
      MemberWizard.steps[MemberWizard.currentStep],
    );
  }

  /* ======================================================
       UI
    ====================================================== */

  function updateSteps() {
    MemberWizard.steps.forEach((step, index) => {
      step.classList.toggle("is-active", index === MemberWizard.currentStep);
    });
  }

  function updateProgress() {
    document
      .querySelectorAll(".member-stepper__item")
      .forEach((item, index) => {
        item.classList.remove("is-active", "is-complete");

        if (index < MemberWizard.currentStep) {
          item.classList.add("is-complete");
        } else if (index === MemberWizard.currentStep) {
          item.classList.add("is-active");
        }
      });
  }

  function updateHeader() {
    MemberWizard.stepNumber.textContent = MemberWizard.currentStep + 1;

    MemberWizard.stepTitle.textContent =
      MemberWizard.titles[MemberWizard.currentStep] ?? "";
  }

  function updateButtons() {
    const first = MemberWizard.currentStep === 0;

    const last = MemberWizard.currentStep >= MemberWizard.steps.length - 1;

    MemberWizard.previousButton.innerHTML = first
      ? `
                <i class="fas fa-times"></i>
                Cancel
            `
      : `
                <i class="fas fa-arrow-left"></i>
                Previous
            `;

    MemberWizard.nextButton.hidden = last;

    MemberWizard.submitButton.hidden = !last;
  }

  function refreshWizard() {
    updateSteps();

    updateProgress();

    updateHeader();

    updateButtons();

    if (MemberReview.update) {
      MemberReview.update();
    }

    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  }

  MemberWizard.refresh = refreshWizard;

  MemberWizard.goTo = function (step) {
    if (step < 0 || step >= MemberWizard.steps.length) {
      return;
    }

    MemberWizard.currentStep = step;

    MemberWizard.refresh();
  };

  /* ======================================================
       EVENTS
    ====================================================== */

  MemberWizard.nextButton.addEventListener("click", () => {
    if (!validateCurrentStep()) {
      return;
    }

    if (MemberWizard.currentStep >= MemberWizard.steps.length - 1) {
      return;
    }

    MemberWizard.currentStep++;

    MemberWizard.refresh();
  });

  MemberWizard.previousButton.addEventListener("click", () => {
    if (MemberWizard.currentStep === 0) {
      if (
        confirm(
          "Discard this member registration and return to the Members page?",
        )
      ) {
        window.location.href = "index.php?route=members";
      }

      return;
    }

    MemberWizard.currentStep--;

    MemberWizard.refresh();
  });

  wizard.querySelectorAll("input, select, textarea").forEach((field) => {
    function update() {
      field.classList.remove("form-control--error");

      updateReview();
    }

    field.addEventListener("input", update);

    field.addEventListener("change", update);
  });

  MemberWizard.refresh();
}

/* ==========================================================
   EMPLOYMENT
========================================================== */

function initializeEmployment() {
  const status = document.getElementById("employment_status");

  if (!status) {
    return;
  }

  const occupation = document.getElementById("occupation");
  const employer = document.getElementById("employer_name");
  const employerAddress = document.getElementById("employer_address");
  const income = document.getElementById("monthly_income");

  const employerLabel = document.getElementById("employer_name_label");

  const addressLabel = document.getElementById("employer_address_label");

  function toggleField(field, disabled) {
    field.disabled = disabled;

    if (disabled) {
      field.value = "";
    }
  }

  function updateEmployment() {
    const value = status.value;

    const disableEmployer = ["Unemployed", "Student", "Retired"].includes(
      value,
    );

    toggleField(employer, disableEmployer);
    toggleField(employerAddress, disableEmployer);
    toggleField(income, disableEmployer);

    occupation.disabled = false;

    if (value === "Self-employed") {
      employerLabel.textContent = "Business Name";
      addressLabel.textContent = "Business Address";
    } else {
      employerLabel.textContent = "Employer / Business Name";

      addressLabel.textContent = "Employer / Business Address";
    }
  }

  status.addEventListener("change", updateEmployment);

  updateEmployment();
}

/* ==========================================================
   EDUCATION
========================================================== */

function initializeEducation() {
  const level = document.getElementById("education_level");

  if (!level) {
    return;
  }

  const course = document.getElementById("course");

  const school = document.getElementById("school");

  const year = document.getElementById("year_graduated");

  const honors = document.getElementById("honors");

  function toggleField(field, disabled) {
    field.disabled = disabled;

    if (disabled) {
      field.value = "";
    }
  }

  function updateEducation() {
    const basicEducation = ["Elementary", "High School"].includes(level.value);

    toggleField(course, basicEducation);
    toggleField(honors, basicEducation);

    school.disabled = false;
    year.disabled = false;
  }

  level.addEventListener("change", updateEducation);

  updateEducation();
}

/* ==========================================================
   BENEFICIARIES
========================================================== */

function initializeBeneficiaries() {
  BeneficiaryList.initialize();
}

/* ==========================================================
   REVIEW
========================================================== */

const MemberReview = {
  update: null,
};

function initializeReview() {
  const wizard = MemberWizard.wizard;

  if (!wizard) {
    return;
  }

  /* ======================================================
       HELPERS
    ====================================================== */

  function getValue(name) {
    const field = wizard.querySelector(`[name="${name}"]`);

    if (!field) {
      return "-";
    }

    const value = field.value.trim();

    return value === "" ? "-" : value;
  }

  /* ======================================================
       FORMATTERS
    ====================================================== */

  function formatName(...parts) {
    return parts.filter((part) => part && part !== "-").join(" ");
  }

  function formatCurrency(value) {
    const amount = parseFloat(value);

    if (Number.isNaN(amount)) {
      return "-";
    }

    return new Intl.NumberFormat("en-PH", {
      style: "currency",
      currency: "PHP",
    }).format(amount);
  }

  function formatDate(value) {
    if (!value || value === "-") {
      return "-";
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
      return value;
    }

    return date.toLocaleDateString("en-PH", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  }

  function formatPhone(value) {
    if (!value || value === "-") {
      return "-";
    }

    return value;
  }

  function formatAddress(...parts) {
    return parts.filter((part) => part && part !== "-").join(", ");
  }

  /* ======================================================
       PERSONAL
    ====================================================== */

  function updatePersonal() {
    const reviewName = document.getElementById("reviewName");

    if (!reviewName) {
      return;
    }

    reviewName.textContent = Formatter.name(
      getValue("first_name"),

      getValue("middle_name"),

      getValue("last_name"),
    );

    document.getElementById("reviewBirth").textContent = Formatter.date(
      getValue("date_of_birth"),
    );

    document.getElementById("reviewSex").textContent = getValue("sex");

    document.getElementById("reviewMarital").textContent =
      getValue("marital_status");
  }

  /* ======================================================
       CONTACT
    ====================================================== */

  function updateContact() {
    document.getElementById("reviewEmail").textContent = getValue("email");

    document.getElementById("reviewPhone1").textContent = Formatter.phone(
      getValue("phone_no_1"),
    );

    document.getElementById("reviewPhone2").textContent = Formatter.phone(
      getValue("phone_no_2"),
    );
  }

  /* ======================================================
       ADDRESS
    ====================================================== */

  function updateAddress() {
    document.getElementById("reviewAddress").textContent = Formatter.address(
      getValue("house_number"),

      getValue("street"),

      getValue("barangay"),

      getValue("town_city"),

      getValue("province"),

      getValue("region"),
    );
  }

  /* ======================================================
       EMPLOYMENT
    ====================================================== */

  function updateEmployment() {
    document.getElementById("reviewEmploymentStatus").textContent =
      getValue("employment_status");

    document.getElementById("reviewOccupation").textContent =
      getValue("occupation");

    document.getElementById("reviewEmployer").textContent =
      getValue("employer_name");

    document.getElementById("reviewIncome").textContent = Formatter.currency(
      getValue("monthly_income"),
    );
  }

  /* ======================================================
       EDUCATION
    ====================================================== */

  function updateEducation() {
    document.getElementById("reviewEducation").textContent =
      getValue("education_level");

    document.getElementById("reviewCourse").textContent = getValue("course");

    document.getElementById("reviewSchool").textContent = getValue("school");

    document.getElementById("reviewYearGraduated").textContent =
      getValue("year_graduated");
  }

  /* ======================================================
       BENEFICIARIES
    ====================================================== */

  function renderBeneficiaries() {
    const container = document.getElementById("reviewBeneficiaries");

    if (!container) {
      return;
    }

    const beneficiaries = BeneficiaryList.getData();

    if (!beneficiaries.length) {
      container.innerHTML = `

                <div class="empty-state">

                    <i class="fas fa-users"></i>

                    <p>No beneficiaries added.</p>

                </div>

            `;

      return;
    }

    container.innerHTML = beneficiaries
      .map(
        (beneficiary) => `

                <div class="review-beneficiary">

                    <div class="review-beneficiary__details">

                        <strong>

                            ${beneficiary.full_name || "-"}

                        </strong>

                        <span>

                            ${beneficiary.relationship || "-"}

                        </span>

                    </div>

                    <div class="review-beneficiary__allocation">

                        ${beneficiary.allocation || 0}%

                    </div>

                </div>

            `,
      )
      .join("");
  }

  /* ======================================================
       UPDATE
    ====================================================== */

  function update() {
    updatePersonal();

    updateContact();

    updateAddress();

    updateEmployment();

    updateEducation();

    renderBeneficiaries();
  }

  /* ======================================================
       PUBLIC API
    ====================================================== */

  MemberReview.update = update;

  /* ======================================================
       REVIEW EVENTS
    ====================================================== */

  document.querySelectorAll("[data-review-step]").forEach((button) => {
    button.addEventListener("click", () => {
      MemberWizard.goTo?.(Number(button.dataset.reviewStep));
    });
  });

  update();
}
