<?php

require_once 'vendor/autoload.php';

use ryan0n\IRCCloudParseLogs\IRCCloudParseLogs;

$objParser = new IRCCloudParseLogs();
$objParser->run();
