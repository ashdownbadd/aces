<?php

if (!function_exists('url')) {

    function url(
        string $route,
        array $params = []
    ): string {

        $query = array_merge(
            ['route' => $route],
            $params
        );

        return 'index.php?' . http_build_query($query);
    }
}
