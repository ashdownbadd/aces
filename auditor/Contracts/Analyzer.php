<?php

declare(strict_types=1);

interface Analyzer
{
    public function analyze(FileIndex $files): array;
}
