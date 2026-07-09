<footer class="page-footer">
    <div class="container">
        <div class="page-footer__content">
            <div class="page-footer__left">
                <strong><?= htmlspecialchars($app['name']) ?></strong>
                <span>•</span>
                <span><?= htmlspecialchars($app['full_name']) ?></span>
            </div>
            <div class="page-footer__right">
                <?= htmlspecialchars($app['copyright']) ?>
            </div>
        </div>
    </div>
</footer>