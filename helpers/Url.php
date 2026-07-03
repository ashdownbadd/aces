<?php

if (!function_exists('url')) {

    function url(string $route): string
    {
        return "index.php?route={$route}";
    }
}