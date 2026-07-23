/**
 * ==========================================================
 * ACES Cooperative
 * Loan Amortization Engine
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  /* ======================================================
   * Public Entry
   * ====================================================== */

  Loan.generateSchedule = function (loan) {
    if (loan.principal <= 0 || loan.terms <= 0 || !loan.startDate) {
      return [];
    }

    switch (loan.amortizationType) {
      case "straight_line":
        return Loan.generateStraightLine(loan);

      case "diminishing_balance":
        return Loan.generateDiminishingBalance(loan);

      case "manual":
        return Loan.generateManual(loan);

      default:
        return [];
    }
  };

  /* ======================================================
   * Helpers
   * ====================================================== */

  Loan.getNumberOfPayments = function (loan) {
    let periods = loan.terms;

    if (loan.loanType === "Micro-Finance Loan") {
      switch (loan.paymentFrequency) {
        case "weekly":
          periods *= 4;
          break;

        case "semi_monthly":
          periods *= 2;
          break;

        case "bi_weekly":
          periods *= 2;
          break;

        case "daily":
          periods *= 30;
          break;
      }
    }

    return periods;
  };

  Loan.nextDueDate = function (date, frequency) {
    const due = new Date(date);

    switch (frequency) {
      case "daily":
        due.setDate(due.getDate() + 1);
        break;

      case "weekly":
        due.setDate(due.getDate() + 7);
        break;

      case "bi_weekly":
        due.setDate(due.getDate() + 14);
        break;

      case "semi_monthly":
        due.setDate(due.getDate() + 15);
        break;

      default:
        due.setMonth(due.getMonth() + 1);
    }

    return due;
  };

  /* ======================================================
   * Straight Line
   * ====================================================== */

  Loan.generateStraightLine = function (loan) {
    const schedule = [];

    const periods = Loan.getNumberOfPayments(loan);

    const principalPerPeriod = loan.principal / periods;

    const interestPerPeriod = loan.principal * (loan.interestRate / 100);

    const payment = principalPerPeriod + interestPerPeriod;

    let balance = loan.principal;

    let dueDate = new Date(loan.startDate);

    for (let i = 1; i <= periods; i++) {
      dueDate = Loan.nextDueDate(dueDate, loan.paymentFrequency);

      balance -= principalPerPeriod;

      schedule.push({
        paymentNo: i,

        dueDate: new Date(dueDate),

        beginningBalance: Loan.round(balance + principalPerPeriod),

        principal: Loan.round(principalPerPeriod),

        interest: Loan.round(interestPerPeriod),

        payment: Loan.round(payment),

        endingBalance: Loan.round(Math.max(balance, 0)),
      });
    }

    return schedule;
  };

  /* ======================================================
   * Diminishing Balance
   * ====================================================== */

  Loan.generateDiminishingBalance = function (loan) {
    const schedule = [];

    const periods = Loan.getNumberOfPayments(loan);

    const principalPerPeriod = loan.principal / periods;

    let balance = loan.principal;

    let dueDate = new Date(loan.startDate);

    for (let i = 1; i <= periods; i++) {
      dueDate = Loan.nextDueDate(dueDate, loan.paymentFrequency);

      const interest = balance * (loan.interestRate / 100);

      const payment = principalPerPeriod + interest;

      schedule.push({
        paymentNo: i,

        dueDate: new Date(dueDate),

        beginningBalance: Loan.round(balance),

        principal: Loan.round(principalPerPeriod),

        interest: Loan.round(interest),

        payment: Loan.round(payment),

        endingBalance: Loan.round(Math.max(balance - principalPerPeriod, 0)),
      });

      balance -= principalPerPeriod;
    }

    return schedule;
  };

  /* ======================================================
   * Manual
   * ====================================================== */

  Loan.generateManual = function () {
    return [];
  };
})();
