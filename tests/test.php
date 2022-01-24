<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Devidw\FolderToFile\FolderToFile;

$merger = new FolderToFile(
    inputDir: __DIR__ . '/test',
    outputFile: __DIR__ . '/Source-Code.adoc',
    allowedExtensions: [
        'html',
        'php',
    ]
);
