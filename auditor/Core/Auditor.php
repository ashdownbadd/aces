<?php

declare(strict_types=1);

final class Auditor
{
    private Console $console;

    private Scanner $scanner;

    private DependencyAnalyzer $dependencyAnalyzer;

    public function __construct(
        Console $console,
        Scanner $scanner,
        DependencyAnalyzer $dependencyAnalyzer
    ) {
        $this->console = $console;
        $this->scanner = $scanner;
        $this->dependencyAnalyzer = $dependencyAnalyzer;
    }

    public function run(): void
    {
        $this->console->title('ACES PROJECT AUDITOR');

        $start = microtime(true);

        $scan = $this->scanner->scan();

        $files = $scan->files();

        $this->console->line();
        $this->console->line('Scanning Project');
        $this->console->line('---------------------------');

        foreach ($scan->folders() as $folder => $count) {

            $this->console->success(
                str_pad($folder, 20) .
                    $count .
                    ' files'
            );
        }

        $this->console->line();

        $this->console->line('Project Summary');
        $this->console->line('---------------------------');

        $this->console->line('PHP   : ' . $files->count('php'));
        $this->console->line('CSS   : ' . $files->count('css'));
        $this->console->line('JS    : ' . $files->count('js'));

        $this->console->line();

        $this->console->line('TOTAL : ' . $files->total());

        $this->console->line();

        /*
        |--------------------------------------------------------------------------
        | Dependency Analyzer
        |--------------------------------------------------------------------------
        */

        $result = $this->dependencyAnalyzer->analyze($files);

        /** @var DependencyGraph $graph */
        $graph = $result['graph'];

        $this->console->line();
        $this->console->line('Dependency Summary');
        $this->console->line('---------------------------');

        foreach ($graph->all() as $target => $sources) {

            $this->console->line(
                str_pad($target, 55) .
                    count($sources) .
                    ' reference(s)'
            );
        }

        $this->console->line();

        $this->console->line(
            'Completed in ' .
                number_format(
                    microtime(true) - $start,
                    4
                ) .
                ' sec'
        );
    }
}
