<?php

function abort(
    int $code,
    string $message = ''
): never
{
    http_response_code($code);

    exit(
        $message ?: "{$code}"
    );
}