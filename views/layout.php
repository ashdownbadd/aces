<!DOCTYPE html>
<html lang="en">

<head>

    <?php include __DIR__ . '/partials/head.php'; ?>

</head>


<body>

    <?php include __DIR__ . '/partials/header.php'; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php include __DIR__ . '/partials/navbar.php'; ?>
    <?php endif; ?>

    <div class="container">

        <?= $content ?>

    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <?php include __DIR__ . '/partials/scripts.php'; ?>

</body>

</html>