/**
 * ==========================================================
 * ACES Cooperative
 * Loan Create Page
 * ==========================================================
 */

document.addEventListener(
  "DOMContentLoaded",

  () => {
    if (
      window.ACES &&
      ACES.loan &&
      typeof ACES.loan.initializeEvents === "function"
    ) {
      ACES.loan.initializeEvents();
    }
  },
);
