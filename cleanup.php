<?php

declare(strict_types=1);

require __DIR__ . '/auditor/bootstrap.php';

$config = require AUDITOR_ROOT . '/config.php';

$console = new Console();
$scanner = new Scanner($config);

$dependencyAnalyzer = new DependencyAnalyzer([
    new IncludeResolver(),
    new RequireResolver(),
    new RenderResolver(),
]);

$unusedFileAnalyzer = new UnusedFileAnalyzer();

$auditor = new Auditor(
    $console,
    $scanner,
    $dependencyAnalyzer,
    $unusedFileAnalyzer
);

$auditor->run();
