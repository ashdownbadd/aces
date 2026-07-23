/**
 * ==========================================================
 * ACES Cooperative
 * Loan Helpers
 * ==========================================================
 */

window.ACES = window.ACES || {};

ACES.loan = ACES.loan || {};

(() => {
  const Loan = ACES.loan;

  /* ======================================================
   * Application State
   * ====================================================== */

  Loan.state = {
    loan: null,

    schedule: [],

    deductions: {},
  };

  /* ======================================================
   * DOM Helpers
   * ====================================================== */

  Loan.$ = (id) => document.getElementById(id);

  Loan.value = (id) => {
    const element = Loan.$(id);

    return element ? element.value : "";
  };

  Loan.number = (id) => {
    const value = parseFloat(Loan.value(id));

    return isNaN(value) ? 0 : value;
  };

  Loan.integer = (id) => {
    const value = parseInt(Loan.value(id));

    return isNaN(value) ? 0 : value;
  };

  Loan.setText = (id, text) => {
    const element = Loan.$(id);

    if (!element) return;

    element.textContent = text;
  };

  Loan.setHTML = (id, html) => {
    const element = Loan.$(id);

    if (!element) return;

    element.innerHTML = html;
  };

  Loan.show = (id) => {
    const element = typeof id === "string" ? Loan.$(id) : id;

    if (!element) return;

    element.style.display = "";
  };

  Loan.hide = (id) => {
    const element = typeof id === "string" ? Loan.$(id) : id;

    if (!element) return;

    element.style.display = "none";
  };

  /* ======================================================
   * Formatting
   * ====================================================== */

  Loan.money = (value) => {
    return Number(value || 0).toLocaleString(
      "en-PH",

      {
        style: "currency",

        currency: "PHP",

        minimumFractionDigits: 2,

        maximumFractionDigits: 2,
      },
    );
  };

  Loan.round = (value) => {
    return Math.round((Number(value) + Number.EPSILON) * 100) / 100;
  };

  Loan.date = (date) => {
    if (!date) return "";

    return new Date(date).toLocaleDateString(
      "en-PH",

      {
        year: "numeric",

        month: "short",

        day: "numeric",
      },
    );
  };

  /* ======================================================
   * Date Helpers
   * ====================================================== */

  Loan.addMonths = (date, months) => {
    const d = new Date(date);

    d.setMonth(d.getMonth() + months);

    return d;
  };

  Loan.addDays = (date, days) => {
    const d = new Date(date);

    d.setDate(d.getDate() + days);

    return d;
  };

  /* ======================================================
   * Summary Builder
   * ====================================================== */

  Loan.buildSummary = (schedule) => {
    let totalInterest = 0;

    let totalRepayment = 0;

    schedule.forEach((payment) => {
      totalInterest += payment.interest;

      totalRepayment += payment.payment;
    });

    return {
      totalInterest: Loan.round(totalInterest),

      totalRepayment: Loan.round(totalRepayment),

      paymentCount: schedule.length,

      firstPayment: schedule.length ? schedule[0].payment : 0,

      lastPayment: schedule.length ? schedule[schedule.length - 1].payment : 0,
    };
  };
})();
