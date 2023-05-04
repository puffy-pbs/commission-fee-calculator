<?php

use Parsers\OperationInfoParser;
use Processors\OperationProcessor;
use Readers\CSVFileReader;

// Require
require_once('vendor/autoload.php');

// Ancient Mac fix
ini_set('auto_detect_line_endings',true);

try {
    // Start transaction processing
    $operationProcessor = new OperationProcessor(
        new CSVFileReader($argv[1]),
        new OperationInfoParser()
    );
} catch (Exception $e) {
    var_dump($e->getMessage());
}

