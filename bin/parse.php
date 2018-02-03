<?php

use ryan0n\IrcCloudParseLogs\Exception\ExportDriverException;
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
    echo getUsage('Error occurred parsing zip file: ' . $e->getMessage());
} catch (ExportDriverNotFoundException $e) {
    echo getUsage('Error occurred initializing export driver: ' . $e->getMessage());
} catch (ExportDriverException $e) {
    echo getUsage('Error occurred in export driver: ' . $e->getMessage());
} catch (Exception $e) {
    echo getUsage('An unknown error occurred: ' . $e->getMessage());
}

function getUsage(string $additionalOutput = null) : string
{
    $output = "\n";
    if (!empty($additionalOutput)) {
        $output .= $additionalOutput . "\n";
    }
    $output .= "\nUsage examples:\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=Json\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=GenericOutput\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=MySQL\n"
        . "php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=GenericOutput --searchPhrase=trump\n"
        . "php bin/parse.php --zipfile=./irccloud-export.zip --exportdriver=genericoutput --searchphrase=\"donald trump\"\n";
    return $output;
}