<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

<?php

c('loan/header');

?>

<form
    action="<?= url('create_loan') ?>"
    method="POST"
    enctype="multipart/form-data"
    class="loan-form"
>

<?php

c('loan/member_section', [

    'members' => $members

]);

c('loan/loan_details');

c('loan/financial_details');

c('loan/real_property_panel');

c('loan/projection');

c('loan/actions');

?>

</form>

</div>

<script src="assets/js/loan-create.js"></script>