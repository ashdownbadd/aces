<?php

declare(strict_types=1);

final class FileIndex
{
    private array $files = [];

    public function add(string $type, FileRecord $file): void
    {
        $this->files[$type][] = $file;
    }

    public function get(string $type): array
    {
        return $this->files[$type] ?? [];
    }

    public function all(): array
    {
        return $this->files;
    }

    public function count(string $type): int
    {
        return count($this->get($type));
    }

    public function total(): int
    {
        return array_sum(array_map('count', $this->files));
    }
}
