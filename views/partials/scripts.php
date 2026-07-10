<?php

$route = $_GET['route'] ?? '';

?>

<script src="assets/js/app.js"></script>

<?php if ($route === 'login'): ?>

    <script src="assets/js/pages/login.js"></script>

<?php endif; ?>

<?php if ($route === 'add_member'): ?>

    <script src="assets/js/core/form.js"></script>

    <script src="assets/js/pages/member-add.js"></script>

<?php endif; ?>