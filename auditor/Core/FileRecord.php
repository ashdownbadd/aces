<?php

declare(strict_types=1);

final class FileRecord
{
    public string $path;
    public string $relativePath;
    public string $directory;
    public string $filename;
    public string $extension;

    public function __construct(
        string $path,
        string $relativePath,
        string $directory,
        string $filename,
        string $extension
    ) {
        $this->path = $path;
        $this->relativePath = $relativePath;
        $this->directory = $directory;
        $this->filename = $filename;
        $this->extension = $extension;
    }
}
