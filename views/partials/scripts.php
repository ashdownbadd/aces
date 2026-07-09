<?php

$route = $_GET['route'] ?? '';

?>

<?php if ($route === 'add_member'): ?>

    <script src="assets/js/member-wizard.js"></script>

<?php endif; ?>

<?php if ($route === 'login'): ?>

    <script src="assets/js/login.js"></script>

<?php endif; ?>