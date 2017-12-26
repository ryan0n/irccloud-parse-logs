<?php

namespace ryan0n\IrcCloudParseLogs;

use ryan0n\IrcCloudParseLogs\ExportDriver\ExportDriverInterface;
use ryan0n\IrcCloudParseLogs\ExportDriver\GenericOutput;
use ryan0n\IrcCloudParseLogs\ExportDriver\Json;
use ryan0n\IrcCloudParseLogs\ExportDriver\MySQL;
use ryan0n\IrcCloudParseLogs\Model\ConfigModel;
use ryan0n\IrcCloudParseLogs\Model\LogLineModel;
use Exception;
use ZipArchive;

class IrcCloudParseLogs
{

    /** @var ConfigModel */
    protected $configModel;

    /** @var ExportDriverInterface */
    protected $exportDriver;

    public function __construct(ConfigModel $configModel) {
        $this->configModel = $configModel;
        $this->exportDriver = $this->exportDriverFactory($configModel->getExportDriver());

        // Second stage zip file validation
        if (!file_exists($this->configModel->getZipFile())) {
            throw new Exception("File doesn't exist.");
        }
    }

    private function exportDriverFactory($type): ExportDriverInterface
    {
        switch ($type) {
            case 'genericoutput':
                return new GenericOutput();
                break;
            case 'json';
                return new Json();
                break;
            case 'mysql':
                return new MySQL();
                break;
            default:
                throw new \InvalidArgumentException("Invalid export driver type '{$type}'.");
                break;
        }
    }

    public function run(): void
    {
        $zip = new ZipArchive;
        $zip->open($this->configModel->getZipFile());

        $logLineCount = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Third stage zip file validation
            if (substr_count($filename, '/') !== 2) {
                throw new Exception('Unexpected zip file structure.');
            }

            $fp = $zip->getStream($zip->getNameIndex($i));

            // Fourth stage zip file validation
            if (!$fp) {
                throw new Exception('Unknown error reading zip file.');
            }
            while (!feof($fp)) {
                $line = fgets($fp);
                $logLineCount++;
                // If searching for a specific string, boost performance by not processing lines that don't contain it.
                if (null !== $this->configModel->getSearchPhrase()) {
                    if (false !== stripos($line, $this->configModel->getSearchPhrase())) {
                        $logLineModel = $this->getPopulatedLogLineModel($filename, $line);
                        $logLineModel->setLineNumber($logLineCount);
                        $this->exportDriver->export($logLineModel);
                    }
                } else {
                    $logLineModel = $this->getPopulatedLogLineModel($filename, $line);
                    $logLineModel->setLineNumber($logLineCount);
                    $this->exportDriver->export($logLineModel);
                }

            }
            fclose($fp);
        }
    }

    private function getPopulatedLogLineModel(string $fileName, string $rawLogLine): LogLineModel
    {
        // Clean up $rawLogLine
        $rawLogLine = rtrim($rawLogLine, "\r\n");

        // init object
        $logLine = new LogLineModel();

        // set raw line
        $logLine->setRawLine($rawLogLine);

        // set the network
        $network = explode('/', $fileName)[1];
        $network = substr($network, strpos($network, '-') + 1, strlen($network));
        $logLine->setNetwork($network);

        // set the channel
        $channel = trim(explode('/', $fileName)[2]);
        $channel = str_replace('.txt', '', $channel);
        $logLine->setChannel($channel);

        // set the date
        $line = explode(' ', $rawLogLine);
        $logLine->setDateTime(
            substr(implode(' ', [$line[0], $line[1]]), 1, strlen(implode(' ', [$line[0], $line[1]])) - 2)
        );
        unset($line[0], $line[1]);

        // set the rest
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
                case '→':
                    $logLine->setType('joined');
                    $logLine->setNick($line[3]);
                    break;
                case '←':
                case '⇐':
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
