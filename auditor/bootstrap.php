<?php

declare(strict_types=1);

define('AUDITOR_ROOT', __DIR__);
define('PROJECT_ROOT', dirname(__DIR__));

require_once AUDITOR_ROOT . '/Contracts/Analyzer.php';

require_once AUDITOR_ROOT . '/Core/Console.php';
require_once AUDITOR_ROOT . '/Core/FileRecord.php';
require_once AUDITOR_ROOT . '/Core/FileIndex.php';
require_once AUDITOR_ROOT . '/Core/Scanner.php';
require_once AUDITOR_ROOT . '/Core/Auditor.php';
require_once AUDITOR_ROOT . '/Core/ScanResult.php';
require_once AUDITOR_ROOT . '/Core/DependencyGraph.php';
require_once AUDITOR_ROOT . '/Analyzers/DependencyAnalyzer.php';
require_once AUDITOR_ROOT . '/Contracts/DependencyExtractor.php';
require_once AUDITOR_ROOT . '/Extractors/IncludeExtractor.php';
require_once AUDITOR_ROOT . '/Extractors/RequireExtractor.php';
