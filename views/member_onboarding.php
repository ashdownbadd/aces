<h1>Initial Share Capital</h1>

<hr>

<p>
    <strong>Member</strong><br>
    <?= htmlspecialchars($onboarding['member_number']) ?>
    -
    <?= htmlspecialchars(
        $onboarding['first_name']
            . ' '
            . $onboarding['last_name']
    ) ?>
</p>

<p>
    <strong>Membership</strong><br>
    <?= htmlspecialchars($onboarding['membership_type']) ?>
</p>

<p>
    <strong>Initial Share Capital</strong><br>
    ₱<?= number_format(
            $onboarding['initial_share_capital'],
            2
        ) ?>
</p>

<hr>

<form method="POST" action="<?= url('submit_initial_capital') ?>">

    <input
        type="hidden"
        name="member_id"
        value="<?= $onboarding['member_id'] ?>">

    <label>

        Voucher Number

    </label>

    <input
        type="text"
        name="voucher_number"
        required>

    <br><br>

    <label>

        Transaction Date

    </label>

    <input
        type="date"
        name="transaction_date"
        value="<?= date('Y-m-d') ?>"
        required>

    <br><br>

    <label>

        Remarks

    </label>

    <textarea
        name="remarks">Initial Share Capital</textarea>

    <br><br>

    <button class="btn btn--primary">

        Submit for Approval

    </button>

</form>