/**
 * ==========================================================
 * ACES Cooperative
 * Loan Helpers
 * ==========================================================
 */

window.ACES = window.ACES || {};
ACES.loan = ACES.loan || {};

const Loan = ACES.loan;

/* ==========================================================
 * DOM
 * ==========================================================
 */

Loan.$ = (id) => document.getElementById(id);

Loan.show = (element) => {
  if (!element) return;

  element.classList.remove("loan-hidden");
};

Loan.hide = (element) => {
  if (!element) return;

  element.classList.add("loan-hidden");
};

/* ==========================================================
 * Formatting
 * ==========================================================
 */

Loan.peso = (value) => {
  return `₱${Number(value || 0).toLocaleString("en-PH", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })}`;
};

Loan.round = (value, decimals = 2) => {
  return Number(Number(value).toFixed(decimals));
};

Loan.formatDate = (date) => {
  if (!(date instanceof Date) || isNaN(date)) {
    return "";
  }

  return date.toLocaleDateString("en-PH", {
    year: "numeric",
    month: "short",
    day: "2-digit",
  });
};

/* ==========================================================
 * Dates
 * ==========================================================
 */

Loan.addMonths = (date, months) => {
  const d = new Date(date);

  d.setMonth(d.getMonth() + months);

  return d;
};

Loan.addWeeks = (date, weeks) => {
  const d = new Date(date);

  d.setDate(d.getDate() + weeks * 7);

  return d;
};

Loan.addDays = (date, days) => {
  const d = new Date(date);

  d.setDate(d.getDate() + days);

  return d;
};
