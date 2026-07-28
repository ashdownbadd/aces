<?php

declare(strict_types=1);

final class ScanResult
{
    private FileIndex $files;

    private array $folders = [];

    public function __construct(FileIndex $files)
    {
        $this->files = $files;
    }

    public function files(): FileIndex
    {
        return $this->files;
    }

    public function addFolder(
        string $folder,
        int $count
    ): void {
        $this->folders[$folder] = $count;
    }

    public function folders(): array
    {
        return $this->folders;
    }
}
