<?php

use ryan0n\IrcCloudParseLogs\Exception\ExportDriverNotFoundException;
use ryan0n\IrcCloudParseLogs\Exception\UnparsableZipFileException;
use ryan0n\IrcCloudParseLogs\IrcCloudParseLogs;
use ryan0n\IrcCloudParseLogs\Model\ConfigModel;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Process command line options
    $options = [];
    foreach ($argv as $arg) {
        if (preg_match_all("/^\-\-(.*?)\=(.*)$/", $arg, $matches)) {
            $options[$matches[1][0]] = $matches[2][0];
        }
    }
    $config = new ConfigModel($options);

    if (empty($config->getZipFile())) {
        throw new UnparsableZipFileException('No zip file given.');
    }

    $objParser = new IrcCloudParseLogs($config);
    $objParser->run();

} catch (UnparsableZipFileException $e) {
    echo "\n" . 'Error occurred parsing zip file: ' . $e->getMessage() . "\n";
    echo getUsage();
} catch (ExportDriverNotFoundException $e) {
    echo "\n" . 'Error occurred initializing export driver: ' . $e->getMessage() . "\n";
    echo getUsage();
} catch (Exception $e) {
    echo "\n" . 'An unknown error occurred: ' . $e->getMessage() . "\n";
    echo getUsage();
}

/**
 * @return string
 */
function getUsage()
{
    return "\nUsage examples:\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=Json\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=GenericOutput\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=MySQL\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=GenericOutput --searchPhrase=trump\n";
}