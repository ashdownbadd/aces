<?php

declare(strict_types=1);

final class RequireExtractor implements DependencyExtractor
{
    public function extract(
        FileRecord $file,
        string $content
    ): array {

        preg_match_all(

            '/require(?:_once)?\s*\(?\s*[\'"]([^\'"]+)[\'"]/',

            $content,

            $matches

        );

        return $matches[1] ?? [];
    }
}
