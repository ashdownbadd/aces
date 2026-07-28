<?php

declare(strict_types=1);

final class Scanner
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function scan(): ScanResult
    {
        $index = new FileIndex();

        $result = new ScanResult($index);

        foreach ($this->config['scan'] as $folder) {

            $path = PROJECT_ROOT . DIRECTORY_SEPARATOR . $folder;

            if (!is_dir($path)) {
                continue;
            }

            $before = $index->total();

            $this->scanDirectory($path, $index);

            $after = $index->total();

            $result->addFolder(
                $folder,
                $after - $before
            );
        }

        return $result;
    }

    private function scanDirectory(
        string $directory,
        FileIndex $index
    ): void {

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                RecursiveDirectoryIterator::SKIP_DOTS
            )
        );

        foreach ($iterator as $file) {

            if (!$file->isFile()) {
                continue;
            }

            $path = $file->getPathname();

            if ($this->shouldIgnore($path)) {
                continue;
            }

            $extension = strtolower($file->getExtension());

            $type = match ($extension) {
                'php' => 'php',
                'css' => 'css',
                'js'  => 'js',
                default => 'other',
            };

            $relative = str_replace(
                PROJECT_ROOT . DIRECTORY_SEPARATOR,
                '',
                $path
            );

            $index->add(
                $type,
                new FileRecord(
                    $path,
                    $relative,
                    dirname($relative),
                    $file->getFilename(),
                    $extension
                )
            );
        }
    }

    private function shouldIgnore(string $path): bool
    {
        foreach ($this->config['ignore'] as $ignore) {

            if (
                str_contains(
                    $path,
                    DIRECTORY_SEPARATOR .
                        $ignore .
                        DIRECTORY_SEPARATOR
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
