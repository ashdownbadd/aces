<?php

if (!function_exists('flash')) {

    function flash(string $key): ?string
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        $message = $_SESSION[$key];

        unset($_SESSION[$key]);

        return $message;
    }
}