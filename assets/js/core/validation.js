/**
 * ==========================================================
 * VALIDATION
 * ==========================================================
 */

const MINIMUM_MEMBER_AGE = 18;

const MAXIMUM_MEMBER_AGE = 100;

const Validation = {
  initialize() {
    document.querySelectorAll("[data-rules]").forEach((field) => {
      field.addEventListener("blur", () => {
        this.validate(field);
      });

      field.addEventListener("input", () => {
        if (field.closest(".form-group")?.querySelector(".form-feedback")) {
          this.validate(field);
        }
      });

      field.addEventListener("change", () => {
        this.validate(field);
      });
    });
  },

  validate(field) {
    this.clearError(field);

    const rules = field.dataset.rules;

    if (!rules) {
      return true;
    }

    for (const rule of rules.split("|")) {
      const validator = this.rules[rule];

      if (!validator) {
        continue;
      }

      const result = validator(field);

      if (result !== true) {
        this.showError(field, result);

        return false;
      }
    }

    return true;
  },

  validateStep(container) {
    let valid = true;

    let firstInvalid = null;

    container.querySelectorAll("[data-rules]").forEach((field) => {
      if (!this.validate(field)) {
        if (!firstInvalid) {
          firstInvalid = field;
        }

        valid = false;
      }
    });

    if (firstInvalid) {
      firstInvalid.focus({
        preventScroll: true,
      });

      firstInvalid.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    }

    return valid;
  },
  showError(field, message) {
    field.classList.add("form-control--error");

    field.setAttribute("aria-invalid", "true");

    const group = field.closest(".form-group");

    if (!group) {
      return;
    }

    let feedback = group.querySelector(".form-feedback");

    if (!feedback) {
      feedback = document.createElement("small");

      feedback.className = "form-feedback form-feedback--error";

      group.appendChild(feedback);
    }

    feedback.textContent = message;
  },

  clearError(field) {
    field.classList.remove("form-control--error");

    field.removeAttribute("aria-invalid");

    field.closest(".form-group")?.querySelector(".form-feedback")?.remove();
  },

  rules: {
    required(field) {
      if (field.type === "checkbox") {
        return field.checked ? true : "This field is required.";
      }

      return field.value.trim() === "" ? "This field is required." : true;
    },

    name(field) {
      const value = field.value.trim();

      if (value === "") {
        return true;
      }

      return /^[A-Za-zÀ-ÿ' -]{2,}$/.test(value)
        ? true
        : "Please enter a valid name.";
    },

    birthdate(field) {
      if (field.value === "") {
        return true;
      }

      const birthDate = new Date(field.value);

      const today = new Date();

      if (birthDate > today) {
        return "Birth date cannot be in the future.";
      }

      let age = today.getFullYear() - birthDate.getFullYear();

      const month = today.getMonth() - birthDate.getMonth();

      if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }

      if (age < MINIMUM_MEMBER_AGE) {
        return "Member must be at least 18 years old.";
      }

      if (age > MAXIMUM_MEMBER_AGE) {
        return "Please enter a valid birth date.";
      }

      return true;
    },

    email(field) {
      const value = field.value.trim();

      if (value === "") {
        return true;
      }

      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
        ? true
        : "Please enter a valid email address.";
    },

    mobile(field) {
      const digits = field.value.replace(/\D/g, "");

      if (digits === "") {
        return true;
      }

      return digits.length === 11
        ? true
        : "Please enter a valid mobile number.";
    },

    numeric(field) {
      const value = field.value.trim();

      if (value === "") {
        return true;
      }

      return !isNaN(value) ? true : "Please enter a valid number.";
    },
  },
};
