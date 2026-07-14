document.addEventListener("DOMContentLoaded", () => {
  initializeForms();
});

function initializeForms() {
  initializeMasks();
  initializeTransforms();
  initializeTrim();
  initializeValidation();
}

/* ==========================================================
   MASKS
========================================================== */

function initializeMasks() {
  const formatters = {
    mobile: formatMobileNumber,
    telephone: formatTelephoneNumber,
  };

  document.querySelectorAll("[data-mask]").forEach((field) => {
    const formatter = formatters[field.dataset.mask];

    if (!formatter) {
      return;
    }

    field.addEventListener("input", () => {
      field.value = formatter(field.value);
    });
  });
}

function formatMobileNumber(value) {
  value = digitsOnly(value).substring(0, 11);

  if (value.length <= 4) {
    return value;
  }

  if (value.length <= 7) {
    return `${value.substring(0, 4)} ${value.substring(4)}`;
  }

  return `${value.substring(0, 4)} ${value.substring(4, 7)} ${value.substring(7)}`;
}

function formatTelephoneNumber(value) {
  value = digitsOnly(value).substring(0, 10);

  if (value.length <= 2) {
    return value;
  }

  if (value.length <= 6) {
    return `(${value.substring(0, 2)}) ${value.substring(2)}`;
  }

  return `(${value.substring(0, 2)}) ${value.substring(2, 6)} ${value.substring(6)}`;
}

/* ==========================================================
   TRANSFORMS
========================================================== */

function initializeTransforms() {
  document.querySelectorAll("[data-transform]").forEach((field) => {
    field.addEventListener("input", () => {
      field.value = transformValue(field.value, field.dataset.transform);
    });
  });
}

function transformValue(value, transform) {
  switch (transform) {
    case "uppercase":
      return value.toUpperCase();

    case "lowercase":
      return value.toLowerCase();

    case "capitalize":
      return value.replace(/\b\w/g, (character) => character.toUpperCase());

    default:
      return value;
  }
}

/* ==========================================================
   AUTO TRIM
========================================================== */

function initializeTrim() {
  document.querySelectorAll("[data-trim]").forEach((field) => {
    field.addEventListener("blur", () => {
      field.value = field.value.trim();
    });
  });
}

/* ==========================================================
   VALIDATION
========================================================== */

function initializeValidation() {
  if (typeof Validation === "undefined") {
    return;
  }

  Validation.initialize();
}

/* ==========================================================
   HELPERS
========================================================== */

function digitsOnly(value) {
  return value.replace(/\D/g, "");
}
