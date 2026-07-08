<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

/**
 * ----------------------------------------------------------
 * Render View
 * ----------------------------------------------------------
 */
function render(
    string $view,
    array $data = []
): string {

    global $app;

    $data['app'] = $app;

    extract($data);

    ob_start();

    require __DIR__
        . '/../views/'
        . $view
        . '.php';

    return ob_get_clean();
}

/**
 * ----------------------------------------------------------
 * Render Component
 * ----------------------------------------------------------
 */
function c(
    string $component,
    array $data = []
): void {

    global $app;

    $data['app'] = $app;

    extract($data);

    require __DIR__
        . '/../views/components/'
        . $component
        . '.php';
}

/**
 * ----------------------------------------------------------
 * Render Form Component
 * ----------------------------------------------------------
 */
function form(
    string $component,
    array $data = []
): void {

    global $app;

    $data['app'] = $app;

    extract($data);

    require __DIR__
        . '/../views/components/forms/'
        . $component
        . '_field.php';
}
