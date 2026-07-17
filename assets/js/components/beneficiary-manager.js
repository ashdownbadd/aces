/**
 * ==========================================================
 * BENEFICIARY LIST
 * ==========================================================
 */

const BeneficiaryList = (() => {
  let beneficiaries = [];

  let container;

  let addButton;

  let allocationLabel;

  function initialize() {
    container = document.getElementById("beneficiaryList");

    addButton = document.getElementById("addBeneficiary");

    allocationLabel = document.getElementById("beneficiaryAllocation");

    if (!container || !addButton) {
      return;
    }

    addButton.addEventListener("click", addBeneficiary);

    render();

    refreshReview();
  }

  function refreshReview() {
    MemberReview.update?.();
  }

  function addBeneficiary() {
    beneficiaries.push({
      id: crypto.randomUUID(),

      full_name: "",

      relationship: "",

      birth_date: "",

      contact_number: "",

      allocation: "",
    });

    render();

    refreshReview();
  }

  function removeBeneficiary(id) {
    beneficiaries = beneficiaries.filter(
      (beneficiary) => beneficiary.id !== id,
    );

    render();

    refreshReview();
  }

  function updateBeneficiary(id, key, value) {
    const currentBeneficiary = beneficiaries.find(
      (beneficiary) => beneficiary.id === id,
    );

    if (!currentBeneficiary) {
      return;
    }

    if (key === "contact_number") {
      currentBeneficiary[key] = Formatter.phoneDigits(value);
    } else {
      currentBeneficiary[key] = value;
    }

    updateAllocation();

    refreshReview();
  }

  function render() {
    container.innerHTML = "";

    beneficiaries.forEach((beneficiary, index) => {
      container.appendChild(createCard(beneficiary, index));
    });

    updateAllocation();
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

                    <label class="form-label">

                        Contact Number

                    </label>

                    <input

                        type="text"

                        class="form-control"

                        value="${beneficiary.contact_number}"

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

    card.querySelectorAll("[data-key]").forEach((field) => {
      if (field.tagName === "SELECT") {
        field.value = beneficiary.relationship;
      }

      field.addEventListener("input", (event) => {
        let value = event.target.value;

        switch (field.dataset.key) {
          case "full_name":
            value = Formatter.capitalizeWords(value);

            event.target.value = value;

            break;

          case "contact_number": {
            value = Formatter.phoneDigits(value);

            event.target.value = Formatter.phone(value);

            break;
          }
          case "allocation": {
            let allocation = parseInt(value, 10);

            if (Number.isNaN(allocation)) {
              value = "";
            } else {
              allocation = Math.min(100, Math.max(0, allocation));

              value = allocation.toString();
            }

            event.target.value = value;

            break;
          }
        }

        updateBeneficiary(
          beneficiary.id,

          field.dataset.key,

          value,
        );
      });
    });

    card.querySelector("[data-remove]").addEventListener(
      "click",

      () => removeBeneficiary(beneficiary.id),
    );

    return card;
  }

  function updateAllocation() {
    const total = beneficiaries.reduce(
      (sum, beneficiary) => sum + (parseInt(beneficiary.allocation, 10) || 0),

      0,
    );

    const exceeded = total > 100;

    allocationLabel.textContent = `${total}%`;

    allocationLabel.classList.toggle("text-danger", exceeded);

    allocationLabel.classList.toggle(
      "text-success",
      !exceeded && total === 100,
    );
  }

  function getData() {
    return beneficiaries;
  }

  function reset() {
    beneficiaries = [];

    render();

    refreshReview();
  }

  return {
    initialize,

    getData,

    reset,

    getTotalAllocation() {
      return beneficiaries.reduce(
        (sum, beneficiary) => sum + (parseInt(beneficiary.allocation, 10) || 0),

        0,
      );
    },
  };
})();
