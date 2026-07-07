<?php

if (!function_exists('redirect')) {

    function redirect(string $route): never
    {
        header("Location: " . url($route));
        exit;
    }
}

if (!function_exists('redirectSuccess')) {

    function redirectSuccess(
        string $route,
        string $message
    ): never {
        flashSuccess($message);

        redirect($route);
    }
}

if (!function_exists('redirectError')) {

    function redirectError(
        string $route,
        string $message
    ): never {
        flashError($message);

        redirect($route);
    }
}
