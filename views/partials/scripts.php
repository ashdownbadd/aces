<?php

$route = $_GET['route'] ?? '';

?>

<script src="assets/js/app.js"></script>

<?php if ($route === 'login'): ?>

    <script src="assets/js/pages/login.js"></script>

<?php endif; ?>

<?php if (in_array($route, ['add_member', 'member_edit'])): ?>

    <script src="assets/js/core/form.js"></script>
    <script src="assets/js/core/validation.js"></script>
    <script src="assets/js/core/formatter.js"></script>
    <script src="assets/js/components/beneficiary-manager.js"></script>
    <script src="assets/js/pages/member-add.js"></script>

<?php endif; ?>

<?php if (in_array($route, ['create_loan'])): ?>

    <script src="assets/js/loan/helpers.js"></script>
    <script src="assets/js/loan/calculator.js"></script>
    <script src="assets/js/loan/amortization.js"></script>
    <script src="assets/js/loan/renderer.js"></script>
    <script src="assets/js/loan/events.js"></script>
    <script src="assets/js/loan/loan-create.js"></script>

<?php endif; ?>