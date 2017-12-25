<?php

namespace ryan0n\IrcCloudParseLogs\Utility;

use ryan0n\IrcCloudParseLogs\Export\ExportInterface;
use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

use \Exception;
use \ZipArchive;


class IRCCloudParseLogs
{

    protected $zipFileName;

    /* @var ExportInterface */
    protected $exportDriver;

    public function __construct(
        string $zipFileName,
        ExportInterface $exportDriver
    )
    {
        $this->zipFileName = $zipFileName;
        $this->exportDriver = $exportDriver;

    }

    public function run(): void
    {
        $this->parseLogFile();
    }
    private function parseLogFile(): void
    {

        $zip = new ZipArchive;
        if ($zip->open($this->zipFileName) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $fp = $zip->getStream($zip->getNameIndex($i));
                if (!$fp || substr_count($filename, '/') !== 2) {
                    throw new Exception('Bad filename.');
                }
                while (!feof($fp)) {
                    $line = fgets($fp);
                    $logLine = $this->getPopulatedLogLine($filename, $line);
                    $this->exportDriver->export($logLine);
                }
                fclose($fp);
            }
        }
    }


    private function getPopulatedLogLine(string $fileName, string $rawLogLine): LogLineModel
    {
        // init DTO
        $logLine = new LogLineModel();

        // raw line
        $logLine->setRawLine($rawLogLine);

        // network
        $network = explode('/', $fileName)[1];
        $network = substr($network, strpos($network, '-') + 1, strlen($network));
        $logLine->setNetwork($network);

        // channel
        $channel = explode('/', $fileName)[2];
        $channel = substr($channel, strpos($channel, '-') + 1, strlen($channel));
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