<?php
// helpers/View.php

if (!function_exists('render')) {

    /**
     * Render a full view.
     */
    function render(string $view, array $data = []): string
    {
        extract($data, EXTR_SKIP);

        ob_start();

        require dirname(__DIR__) . "/views/{$view}.php";

        return ob_get_clean();
    }
}

if (!function_exists('component')) {

    /**
     * Render a reusable component.
     */
    function component(string $component, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        require dirname(__DIR__) . "/views/components/{$component}.php";
    }
}

if (!function_exists('c')) {

    /**
     * Short alias for component()
     */
    function c(string $component, array $data = []): void
    {
        component($component, $data);
    }
}

if (!function_exists('capture')) {

    function capture(callable $callback): string
    {
        ob_start();

        $callback();

        return ob_get_clean();
    }
}
