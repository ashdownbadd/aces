/**
 * ==========================================================
 * ACES Cooperative
 * Loan Amortization Engine
 * ==========================================================
 */

(() => {
  const Loan = ACES.loan;

  Loan.generateSchedule = function (loan) {
    if (loan.principal <= 0 || loan.terms <= 0 || !loan.startDate) {
      return [];
    }

    switch (loan.amortizationType) {
      case "Straight Line":
        return Loan.generateStraightLine(loan);

      case "Diminishing Balance":
        return Loan.generateDiminishingBalance(loan);

      case "Manual":
        return Loan.generateManual(loan);

      default:
        return [];
    }
  };

  /* ======================================================
   * Straight-Line
   * ====================================================== */

  Loan.generateStraightLine = function (loan) {
    const schedule = [];

    let balance = loan.principal;

    const periods = loan.numberOfPayments;

    const principalPerPeriod = loan.principal / periods;

    const interestPerPeriod = loan.totalInterest / periods;

    const payment = principalPerPeriod + interestPerPeriod;

    const dueDate = new Date(loan.startDate);

    for (let i = 1; i <= periods; i++) {
      switch (loan.paymentFrequency) {
        case "Weekly":
          dueDate.setDate(dueDate.getDate() + 7);

          break;

        case "Bi-Monthly":
          dueDate.setDate(dueDate.getDate() + 15);

          break;

        default:
          dueDate.setMonth(dueDate.getMonth() + 1);
      }

      balance -= principalPerPeriod;

      schedule.push({
        paymentNo: i,

        dueDate: new Date(dueDate),

        principal: Loan.round(principalPerPeriod),

        interest: Loan.round(interestPerPeriod),

        payment: Loan.round(payment),

        balance: Loan.round(Math.max(balance, 0)),
      });
    }

    return schedule;
  };

  /* ======================================================
   * Diminishing Balance
   * ====================================================== */

  Loan.generateDiminishingBalance = function (loan) {
    const schedule = [];

    let balance = loan.principal;

    const dueDate = new Date(loan.startDate);

    const principalPerPeriod = loan.principal / loan.numberOfPayments;

    for (let i = 1; i <= loan.numberOfPayments; i++) {
      switch (loan.paymentFrequency) {
        case "Weekly":
          dueDate.setDate(dueDate.getDate() + 7);

          break;

        case "Bi-Monthly":
          dueDate.setDate(dueDate.getDate() + 15);

          break;

        default:
          dueDate.setMonth(dueDate.getMonth() + 1);
      }

      const interest = balance * (loan.interestRate / 100);

      const payment = principalPerPeriod + interest;

      balance -= principalPerPeriod;

      schedule.push({
        paymentNo: i,

        dueDate: new Date(dueDate),

        principal: Loan.round(principalPerPeriod),

        interest: Loan.round(interest),

        payment: Loan.round(payment),

        balance: Loan.round(Math.max(balance, 0)),
      });
    }

    return schedule;
  };

  /* ======================================================
   * Manual
   * ====================================================== */

  Loan.generateManual = function (loan) {
    const schedule = [];

    let balance = loan.principal;

    const dueDate = new Date(loan.startDate);

    for (let i = 1; i <= loan.numberOfPayments; i++) {
      switch (loan.paymentFrequency) {
        case "Weekly":
          dueDate.setDate(dueDate.getDate() + 7);

          break;

        case "Bi-Monthly":
          dueDate.setDate(dueDate.getDate() + 15);

          break;

        default:
          dueDate.setMonth(dueDate.getMonth() + 1);
      }

      const interest = balance * (loan.interestRate / 100);

      let principal = loan.manualPayment - interest;

      if (principal < 0) {
        principal = 0;
      }

      balance -= principal;

      schedule.push({
        paymentNo: i,

        dueDate: new Date(dueDate),

        principal: Loan.round(principal),

        interest: Loan.round(interest),

        payment: Loan.round(loan.manualPayment),

        balance: Loan.round(Math.max(balance, 0)),
      });

      if (balance <= 0) {
        break;
      }
    }

    return schedule;
  };
})();
