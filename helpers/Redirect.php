<?php

if (!function_exists('redirect')) {

    function redirect(
        string $route,
        array $params = []
    ): never {

        header(
            'Location: ' . url($route, $params)
        );

        exit;
    }
}

if (!function_exists('redirectSuccess')) {

    function redirectSuccess(
        string $route,
        string $message,
        array $params = []
    ): never {

        flashSuccess($message);

        redirect(
            $route,
            $params
        );
    }
}

if (!function_exists('redirectError')) {

    function redirectError(
        string $route,
        string $message,
        array $params = []
    ): never {

        flashError($message);

        redirect(
            $route,
            $params
        );
    }
}
