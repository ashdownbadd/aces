<?php

if (!defined('ALLOW_ACCESS')) {
    die('Direct access prohibited.');
}

function render(string $view, array $data = []): string
{
    extract($data);

    ob_start();

    include __DIR__ . "/../views/{$view}.php";

    return ob_get_clean();
}