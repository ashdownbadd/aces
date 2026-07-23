/**
 * ==========================================================
 * ACES Cooperative
 * Loan Module Bootstrap
 * ==========================================================
 */

document.addEventListener(
  "DOMContentLoaded",

  () => {
    if (window.ACES && ACES.loan && typeof ACES.loan.init === "function") {
      ACES.loan.init();
    }
  },
);
