<?php

if (!function_exists('redirect')) {

    function redirect(string $route): never
    {
        header("Location: index.php?route={$route}");
        exit;
    }
}
