<?php

namespace ryan0n\IrcCloudParseLogs\Utility;

use ryan0n\IrcCloudParseLogs\Export\ExportInterface;
use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

use \Exception;
use \ZipArchive;

class IRCCloudParseLogs
{

    /* @var string */
    protected $zipFileName;

    /* @var ExportInterface */
    protected $exportDriver;

    public function __construct(
        string $zipFileName,
        ExportInterface $exportDriver
    ) {
        $this->zipFileName = $zipFileName;
        $this->exportDriver = $exportDriver;

        // Third stage zip file validation
        if (!file_exists($this->zipFileName)) {
            throw new Exception("File doesn't exist.");
        }
    }

    public function run(): void
    {
        $this->parseLogFile();
    }

    private function parseLogFile(): void
    {

        $zip = new ZipArchive;
        $zip->open($this->zipFileName);

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Third stage zip file validation
            if (substr_count($filename, '/') !== 2) {
                throw new Exception('Unexpected file structure.');
            }

            $fp = $zip->getStream($zip->getNameIndex($i));

            // Fourth stage zip file validation
            if (!$fp) {
                throw new Exception('Unknown error reading zip.');
            }

            while (!feof($fp)) {
                $line = fgets($fp);
                $logLine = $this->getPopulatedLogLine($filename, $line);
                $this->exportDriver->export($logLine);
            }
            fclose($fp);
        }
    }


    private function getPopulatedLogLine(string $fileName, string $rawLogLine): LogLineModel
    {
        // init object
        $logLine = new LogLineModel();

        // raw line
        $logLine->setRawLine($rawLogLine);

        // network
        $network = explode('/', $fileName)[1];
        $network = substr($network, strpos($network, '-') + 1, strlen($network));
        $logLine->setNetwork($network);

        // channel
        $channel = trim(explode('/', $fileName)[2]);
        $channel = str_replace('.txt', '', $channel);
        $logLine->setChannel($channel);

        // The rest
        $line = explode(' ', $rawLogLine);
        $logLine->setDateTime(
            substr(implode(' ', [$line[0], $line[1]]), 1, strlen(implode(' ', [$line[0], $line[1]])) - 2)
        );
        unset($line[0], $line[1]);

        if ($line[2][0] === '<') {
            $logLine->setType('message');
            $logLine->setNick(substr($line[2], 1, strlen($line[2]) - 2));
            unset($line[2]);
            $logLine->setMessage(implode(' ', $line));
        } elseif (trim($line[2]) === '—') {
            $logLine->setType('message');
            $logLine->setNick(substr($line[3], 1, strlen($line[3]) - 2));
            unset($line[2], $line[3]);
            $logLine->setMessage(implode(' ', $line));
        } else {
            switch ($line[2]) {
                case "→":
                    $logLine->setType('joined');
                    $logLine->setNick($line[3]);
                    break;
                case "←":
                case "⇐":
                    if ($line[6] !== 'channel:') {
                        $logLine->setType('parted');
                        $logLine->setNick($line[3]);
                    }
                    break;
                default:
                    $logLine->setType('other');
                    $logLine->setMessage(implode(' ', $line));
                    break;
            }
        }

        return $logLine;
    }
}