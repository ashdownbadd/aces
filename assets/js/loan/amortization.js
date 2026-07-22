/**
 * ==========================================================
 * ACES Cooperative
 * Loan Amortization
 * Mirrors helpers/AmortizationEngine.php
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ==========================================================
   * Public
   * ==========================================================
   */

  Loan.generateSchedule = function (loan) {
    if (loan.principal <= 0 || loan.terms <= 0 || !loan.startDate) {
      return [];
    }

    if (loan.loanType === "Micro-Finance Loan") {
      return Loan.generateMicroFinance(loan);
    }

    switch (loan.amortizationType) {
      case "Straight-line":
        return Loan.generateStraightLine(loan);

      case "Diminishing Balance":
        return Loan.generateDiminishingBalance(loan);

      case "Manual":
        return Loan.generateManual(loan);

      default:
        return [];
    }
  };

  /* ==========================================================
   * Straight-line
   * ==========================================================
   */

  Loan.generateStraightLine = function (loan) {
    const schedule = [];

    const fixedPrincipal = loan.principal / loan.terms;

    const interestPerPeriod = loan.principal * (loan.interestRate / 100);

    let balance = loan.principal;

    const startDate = new Date(loan.startDate);

    for (let i = 1; i <= loan.terms; i++) {
      balance -= fixedPrincipal;

      schedule.push({
        paymentNo: i,

        dueDate: Loan.addMonths(startDate, i),

        principal: Loan.round(fixedPrincipal),

        interest: Loan.round(interestPerPeriod),

        payment: Loan.round(fixedPrincipal + interestPerPeriod),

        balance: Loan.round(Math.max(balance, 0)),
      });
    }

    return schedule;
  };

  /* ==========================================================
   * Diminishing Balance
   * ==========================================================
   */

  Loan.generateDiminishingBalance = function (loan) {
    const schedule = [];

    const fixedPrincipal = loan.principal / loan.terms;

    let balance = loan.principal;

    const startDate = new Date(loan.startDate);

    for (let i = 1; i <= loan.terms; i++) {
      const interest = balance * (loan.interestRate / 100);

      balance -= fixedPrincipal;

      schedule.push({
        paymentNo: i,

        dueDate: Loan.addMonths(startDate, i),

        principal: Loan.round(fixedPrincipal),

        interest: Loan.round(interest),

        payment: Loan.round(fixedPrincipal + interest),

        balance: Loan.round(Math.max(balance, 0)),
      });
    }

    return schedule;
  };

  /* ==========================================================
   * Manual
   * ==========================================================
   */

  Loan.generateManual = function (loan) {
    const schedule = [];

    let balance = loan.principal;

    const startDate = new Date(loan.startDate);

    for (let i = 1; i <= loan.terms; i++) {
      const interest = balance * (loan.interestRate / 100);

      let principal = loan.manualPayment - interest;

      if (principal < 0) {
        principal = 0;
      }

      balance -= principal;

      if (balance < 0) {
        balance = 0;
      }

      schedule.push({
        paymentNo: i,

        dueDate: Loan.addMonths(startDate, i),

        principal: Loan.round(principal),

        interest: Loan.round(interest),

        payment: Loan.round(principal + interest),

        balance: Loan.round(balance),
      });
    }

    return schedule;
  };

  /* ==========================================================
   * Micro-Finance
   * ==========================================================
   */

  Loan.generateMicroFinance = function (loan) {
    const schedule = [];

    let multiplier = 1;

    switch (loan.paymentFrequency) {
      case "Bi-Monthly":
        multiplier = 2;
        break;

      case "Weekly":
        multiplier = 4;
        break;
    }

    const totalPeriods = loan.terms * multiplier;

    const ratePerPeriod = loan.interestRate / 100 / multiplier;

    const principalPerPeriod = loan.principal / totalPeriods;

    const interestPerPeriod = loan.principal * ratePerPeriod;

    let balance = loan.principal;

    let dueDate = new Date(loan.startDate);

    for (let i = 1; i <= totalPeriods; i++) {
      switch (loan.paymentFrequency) {
        case "Weekly":
          dueDate = Loan.addDays(dueDate, 7);
          break;

        case "Bi-Monthly":
          dueDate = Loan.addDays(dueDate, 15);
          break;

        default:
          dueDate = Loan.addMonths(dueDate, 1);
      }

      balance -= principalPerPeriod;

      schedule.push({
        paymentNo: i,

        dueDate,

        principal: Loan.round(principalPerPeriod),

        interest: Loan.round(interestPerPeriod),

        payment: Loan.round(principalPerPeriod + interestPerPeriod),

        balance: Loan.round(Math.max(balance, 0)),
      });
    }

    return schedule;
  };
})();
