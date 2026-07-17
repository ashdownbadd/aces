/* ==========================================================
   FORMATTER
========================================================== */

const Formatter = {
  /* ======================================================
       TEXT
    ====================================================== */

  titleCase(value) {
    if (!value) {
      return "";
    }

    return value
      .toLowerCase()
      .replace(/\b\w/g, (character) => character.toUpperCase());
  },

  name(...parts) {
    return parts
      .filter((part) => part && part !== "-")
      .map((part) => this.titleCase(part))
      .join(" ");
  },

  /* ======================================================
       PHONE
    ====================================================== */

  phone(value) {
    if (!value || value === "-") {
      return "-";
    }

    const digits = value.replace(/\D/g, "").slice(0, 11);

    if (digits.length <= 4) {
      return digits;
    }

    if (digits.length <= 7) {
      return digits.replace(/(\d{4})(\d+)/, "$1 $2");
    }

    return digits.replace(/(\d{4})(\d{3})(\d+)/, "$1 $2 $3");
  },

  /* ======================================================
       DATE
    ====================================================== */

  date(value) {
    if (!value || value === "-") {
      return "-";
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
      return value;
    }

    return date.toLocaleDateString(
      "en-PH",

      {
        year: "numeric",

        month: "long",

        day: "numeric",
      },
    );
  },

  /* ======================================================
       CURRENCY
    ====================================================== */

  currency(value) {
    const amount = Number(this.currencyDigits(value));

    if (Number.isNaN(amount)) {
      return "-";
    }

    return new Intl.NumberFormat(
      "en-PH",

      {
        style: "currency",

        currency: "PHP",
      },
    ).format(amount);
  },

  /* ======================================================
       ADDRESS
    ====================================================== */

  address(...parts) {
    return parts.filter((part) => part && part !== "-").join(", ");
  },

  /* ======================================================
   INPUT HELPERS
====================================================== */

  phoneDigits(value) {
    return value.replace(/\D/g, "").slice(0, 11);
  },

  capitalizeWords(value) {
    return this.titleCase(value);
  },

  /* ======================================================
   CURRENCY INPUT
====================================================== */

  currencyDigits(value) {
    return value.replace(/[^\d]/g, "");
  },

  currencyInput(value) {
    const digits = this.currencyDigits(value);

    if (!digits) {
      return "";
    }

    return Number(digits).toLocaleString("en-PH");
  },
};
