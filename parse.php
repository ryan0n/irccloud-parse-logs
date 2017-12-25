<?php

require_once 'vendor/autoload.php';

use ryan0n\IRCCloudParseLogs\ExportDriver\StandardOutput;
use ryan0n\IRCCloudParseLogs\IRCCloudParseLogs;

$objParser = new IRCCloudParseLogs(
    $argv[1],
    new StandardOutput()
);
$objParser->run();
