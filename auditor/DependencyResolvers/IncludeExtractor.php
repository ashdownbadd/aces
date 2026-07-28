<?php

declare(strict_types=1);

final class IncludeExtractor implements DependencyExtractor
{
    public function extract(
        FileRecord $file,
        string $content
    ): array {

        preg_match_all(

            '/include(?:_once)?\s*\(?\s*[\'"]([^\'"]+)[\'"]/',

            $content,

            $matches

        );

        return $matches[1] ?? [];
    }
}
