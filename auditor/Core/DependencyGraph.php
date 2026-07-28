<?php

declare(strict_types=1);

final class DependencyGraph
{
    private array $graph = [];

    public function add(string $target, string $source): void
    {
        if (!isset($this->graph[$target])) {
            $this->graph[$target] = [];
        }

        if (!in_array($source, $this->graph[$target], true)) {
            $this->graph[$target][] = $source;
        }
    }

    public function references(string $target): array
    {
        return $this->graph[$target] ?? [];
    }

    public function all(): array
    {
        return $this->graph;
    }
}
