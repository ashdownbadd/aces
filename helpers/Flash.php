<?php

if (!function_exists('flashSuccess')) {

    function flashSuccess(string $message): void
    {
        $_SESSION['success_message'] = $message;
    }
}

if (!function_exists('flashError')) {

    function flashError(string $message): void
    {
        $_SESSION['error_message'] = $message;
    }
}
