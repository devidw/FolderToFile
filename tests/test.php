<?php

require_once(dirname(__DIR__).'/src/FolderToFile.class.php');

$merger = new FolderToFile(
    inputDir: __DIR__.'/test',
    outputFile: __DIR__.'/Source-Code.adoc',
    allowedExtensions: ['php']
);
