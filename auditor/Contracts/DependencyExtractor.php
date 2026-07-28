<?php

declare(strict_types=1);

final class DependencyGraph
{
    private array $graph = [];

    public function add(
        string $dependency,
        string $source
    ): void {

        if (!isset($this->graph[$dependency])) {
            $this->graph[$dependency] = [];
        }

        if (!in_array($source, $this->graph[$dependency], true)) {
            $this->graph[$dependency][] = $source;
        }
    }

    public function all(): array
    {
        return $this->graph;
    }

    public function references(string $dependency): array
    {
        return $this->graph[$dependency] ?? [];
    }
}
