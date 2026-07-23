<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page loan-page">

    <?php

    c('page_header', [
        'title' => 'Create Loan',
        'description' => 'Create and configure a new loan application for a cooperative member.'
    ]);

    ?>

    <?php c('flash_messages'); ?>

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

            <?php c('loan/loan_details'); ?>

        </section>

        <?php c('loan/repayment_details'); ?>

        <section class="loan-summary-grid">

            <?php c('loan/summary'); ?>

            <?php c('loan/estimated_payment'); ?>

        </section>

        <?php c('loan/projection'); ?>

    </form>

</div>