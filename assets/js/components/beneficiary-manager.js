const BeneficiaryList = (() => {
  let beneficiaries = [];

  let container;
  let addButton;
  let allocationLabel;

  /* ==========================================================
     INITIALIZE
  ========================================================== */

  function initialize() {
    container = document.getElementById("beneficiaryList");
    addButton = document.getElementById("addBeneficiary");
    allocationLabel = document.getElementById("beneficiaryAllocation");

    if (!container || !addButton) {
      return;
    }

    addButton.addEventListener("click", addBeneficiary);

    refresh();
  }

  /* ==========================================================
     REFRESH
  ========================================================== */

  function refresh() {
    render();
    updateAllocation();
    refreshReview();
  }

  function refreshReview() {
    MemberReview.update?.();
  }

  /* ==========================================================
     BENEFICIARIES
  ========================================================== */

  function addBeneficiary() {
    beneficiaries.push({
      id: crypto.randomUUID(),
      full_name: "",
      relationship: "",
      birth_date: "",
      contact_number: "",
      allocation: "",
    });

    refresh();

    requestAnimationFrame(() => {
      container?.lastElementChild
        ?.querySelector('[data-key="full_name"]')
        ?.focus();
    });
  }

  function removeBeneficiary(id) {
    beneficiaries = beneficiaries.filter(
      (beneficiary) => beneficiary.id !== id,
    );

    refresh();
  }

  function updateBeneficiary(id, key, value) {
    const beneficiary = beneficiaries.find((item) => item.id === id);

    if (!beneficiary) {
      return;
    }

    switch (key) {
      case "full_name":
        beneficiary.full_name = value.trim();
        break;

      case "relationship":
        beneficiary.relationship = value;
        break;

      case "birth_date":
        beneficiary.birth_date = value;
        break;

      case "contact_number":
        beneficiary.contact_number = Formatter.phoneDigits(value);
        break;

      case "allocation":
        beneficiary.allocation = value;
        break;

      default:
        beneficiary[key] = value;
    }

    updateAllocation();
    refreshReview();
  }

  /* ==========================================================
     RENDER
  ========================================================== */

  function render() {
    container.innerHTML = "";

    beneficiaries.forEach((beneficiary, index) => {
      container.appendChild(createCard(beneficiary, index));
    });

    if (!beneficiaries.length) {
      updateAllocation();
    }
  }

  function createCard(beneficiary, index) {
    const card = document.createElement("div");

    card.className = "beneficiary-card";

    card.innerHTML = `
    <div class="beneficiary-card__header">

      <h3>
        Beneficiary #${index + 1}
      </h3>

      <button
        type="button"
        class="btn btn--danger btn--sm"
        data-remove>

        <i class="fas fa-trash"></i>
        Remove

      </button>

    </div>

    <div class="form-grid form-grid--2">

      <div class="form-group">

        <label class="form-label form-label--required">
          Full Name
        </label>

        <input
          type="text"
          class="form-control"
          value="${beneficiary.full_name}"
          data-key="full_name">

      </div>

      <div class="form-group">

        <label class="form-label form-label--required">
          Relationship
        </label>

        <select
          class="form-control"
          data-key="relationship">

          <option value="">Select Relationship</option>
          <option value="Spouse">Spouse</option>
          <option value="Father">Father</option>
          <option value="Mother">Mother</option>
          <option value="Son">Son</option>
          <option value="Daughter">Daughter</option>
          <option value="Brother">Brother</option>
          <option value="Sister">Sister</option>
          <option value="Grandfather">Grandfather</option>
          <option value="Grandmother">Grandmother</option>
          <option value="Grandson">Grandson</option>
          <option value="Granddaughter">Granddaughter</option>
          <option value="Other">Other</option>

        </select>

      </div>

      <div class="form-group">

        <label class="form-label">
          Birth Date
        </label>

        <input
          type="date"
          class="form-control"
          value="${beneficiary.birth_date}"
          data-key="birth_date">

      </div>

      <div class="form-group">

        <label class="form-label form-label--required">
          Contact Number
        </label>

        <input
          type="text"
          class="form-control"
          maxlength="13"
          value="${Formatter.phone(beneficiary.contact_number)}"
          data-key="contact_number">

      </div>

      <div class="form-group">

        <label class="form-label form-label--required">
          Allocation (%)
        </label>

        <input
          type="number"
          class="form-control"
          min="0"
          max="100"
          value="${beneficiary.allocation}"
          data-key="allocation">

      </div>

    </div>
  `;

    card.querySelector('[data-key="relationship"]').value =
      beneficiary.relationship;

    card.querySelectorAll("[data-key]").forEach((field) => {
      field.addEventListener("input", (event) => {
        let value = event.target.value;

        switch (field.dataset.key) {
          case "full_name":
            value = Formatter.capitalizeWords(value);

            event.target.value = value;

            break;

          case "contact_number":
            value = Formatter.phoneDigits(value);

            event.target.value = Formatter.phone(value);

            break;

          case "allocation": {
            let allocation = parseInt(value, 10);

            if (Number.isNaN(allocation)) {
              value = "";
            } else {
              allocation = Math.max(0, Math.min(100, allocation));

              value = allocation.toString();
            }

            event.target.value = value;

            break;
          }
        }

        field.classList.remove("form-control--error");

        updateBeneficiary(beneficiary.id, field.dataset.key, value);
      });

      field.addEventListener("change", () => {
        refreshReview();
      });
    });

    card.querySelector("[data-remove]").addEventListener("click", () => {
      removeBeneficiary(beneficiary.id);
    });

    return card;
  }

  /* ==========================================================
     VALIDATION
  ========================================================== */

  function validate() {
    let valid = true;

    const relationships = new Set();

    container.querySelectorAll(".beneficiary-card").forEach((card) => {
      const fields = card.querySelectorAll("[data-key]");

      fields.forEach((field) => {
        field.classList.remove("form-control--error");

        const key = field.dataset.key;

        const value = field.value.trim();

        const required = [
          "full_name",
          "relationship",
          "contact_number",
          "allocation",
        ];

        if (required.includes(key) && value === "") {
          field.classList.add("form-control--error");
          valid = false;
          return;
        }

        if (key === "relationship") {
          if (relationships.has(value)) {
            field.classList.add("form-control--error");
            valid = false;
          } else {
            relationships.add(value);
          }
        }
      });
    });

    const total = getTotalAllocation();

    if (total !== 100) {
      allocationLabel.classList.add("text-danger");
      valid = false;
    }

    return valid;
  }

  /* ==========================================================
     ALLOCATION
  ========================================================== */

  function updateAllocation() {
    const total = getTotalAllocation();

    allocationLabel.textContent = `${total}%`;

    allocationLabel.classList.remove(
      "text-danger",
      "text-success",
      "text-warning",
    );

    if (total > 100) {
      allocationLabel.classList.add("text-danger");
    } else if (total === 100) {
      allocationLabel.classList.add("text-success");
    } else {
      allocationLabel.classList.add("text-warning");
    }
  }

  function getTotalAllocation() {
    return beneficiaries.reduce(
      (sum, beneficiary) => sum + (parseInt(beneficiary.allocation, 10) || 0),
      0,
    );
  }

  /* ==========================================================
     DATA
  ========================================================== */

  function getData() {
    return beneficiaries;
  }

  function reset() {
    beneficiaries = [];

    refresh();
  }

  /* ==========================================================
     PUBLIC API
  ========================================================== */

  return {
    initialize,

    validate,

    getData,

    getTotalAllocation,

    reset,
  };
})();
