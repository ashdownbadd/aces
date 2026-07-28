<?php

declare(strict_types=1);

final class Console
{
    public function title(string $text): void
    {
        echo PHP_EOL;
        echo str_repeat('=', 60) . PHP_EOL;
        echo $text . PHP_EOL;
        echo str_repeat('=', 60) . PHP_EOL;
    }

    public function line(string $text = ''): void
    {
        echo $text . PHP_EOL;
    }

    public function success(string $text): void
    {
        echo "[✓] {$text}" . PHP_EOL;
    }

    public function warning(string $text): void
    {
        echo "[!] {$text}" . PHP_EOL;
    }

    public function error(string $text): void
    {
        echo "[✗] {$text}" . PHP_EOL;
    }
}
