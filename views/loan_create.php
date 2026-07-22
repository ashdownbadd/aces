<form
    action="<?= url('create_loan') ?>"
    method="POST"
    enctype="multipart/form-data"
    class="form">

    <section class="loan-top-grid">

        <?php

        c('loan/borrower_information', [
            'members' => $members
        ]);

        ?>

        <?php c('loan/repayment_details'); ?>

    </section>

    <section class="loan-summary-grid">

        <?php c('loan/summary'); ?>

        <?php c('loan/estimated_payment'); ?>

    </section>

    <?php c('loan/real_property_panel'); ?>

    <?php c('loan/projection'); ?>

</form>