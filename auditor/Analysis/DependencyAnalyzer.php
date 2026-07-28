<?php

declare(strict_types=1);

final class DependencyAnalyzer implements Analyzer
{
    /**
     * @var DependencyExtractor[]
     */
    private array $extractors;

    public function __construct()
    {
        $this->extractors = [

            new IncludeExtractor(),

            new RequireExtractor(),

        ];
    }

    public function analyze(
        FileIndex $files
    ): array {

        $graph = new DependencyGraph();

        foreach ($files->get('php') as $file) {

            $content = file_get_contents($file->path);

            foreach ($this->extractors as $extractor) {

                $dependencies = $extractor->extract(
                    $file,
                    $content
                );

                foreach ($dependencies as $dependency) {

                    $graph->add(
                        $dependency,
                        $file->relativePath
                    );
                }
            }
        }

        return [

            'graph' => $graph

        ];
    }
}
