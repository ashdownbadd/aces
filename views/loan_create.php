<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

    <?php

    c('page_header', [
        'title' => 'Create Loan',
        'description' => 'Create a new cooperative loan account and automatically generate its amortization schedule.'
    ]);

    ?>

    <?php c('flash_messages'); ?>

    <div class="page__actions">

        <a
            href="<?= url('amortization_dashboard') ?>"
            class="btn btn--secondary">

            <i class="fas fa-arrow-left"></i>

            Back to Loans

        </a>

    </div>

    <form
        action="<?= url('create_loan') ?>"
        method="POST"
        enctype="multipart/form-data"
        class="form">

        <div class="form">

            <?php

            c('loan/borrower_information', [
                'members' => $members
            ]);

            ?>

            <?php c('loan/repayment_details'); ?>

            <?php c('loan/real_property_panel'); ?>

            <?php c('loan/summary'); ?>

            <?php c('loan/estimated_payment'); ?>

            <?php c('loan/projection'); ?>

            <?php c('loan/actions'); ?>

        </div>

    </form>

</div>