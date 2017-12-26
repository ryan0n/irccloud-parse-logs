<?php

namespace ryan0n\IrcCloudParseLogs;

use ryan0n\IrcCloudParseLogs\Exception\ {
    ExportDriverNotFoundException,
    UnparsableZipFileException
};
use ryan0n\IrcCloudParseLogs\ExportDriver\ {
    ExportDriverInterface,
    GenericOutput,
    Json,
    MySQL
};
use ryan0n\IrcCloudParseLogs\Model\ {
    ConfigModel,
    LogLineModel
};
use ZipArchive;

class IrcCloudParseLogs
{
    /** @var ConfigModel $configModel */
    protected $configModel;

    /** @var ExportDriverInterface $exportDriver */
    protected $exportDriver;

    /**
     * @param ConfigModel $configModel
     * @throws UnparsableZipFileException
     */
    public function __construct(ConfigModel $configModel) {
        $this->configModel = $configModel;
        $this->exportDriver = $this->exportDriverFactory($configModel->getExportDriver());

        // zip file validation
        if (!file_exists($this->configModel->getZipFile())) {
            throw new UnparsableZipFileException("File doesn't exist.");
        }
    }

    /**
     * @param string $type
     * @return ExportDriverInterface
     * @throws ExportDriverNotFoundException
     */
    private function exportDriverFactory(string $type): ExportDriverInterface
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
                throw new ExportDriverNotFoundException("Invalid export driver type '{$type}'.");
                break;
        }
    }

    /**
     * @throws UnparsableZipFileException
     */
    public function run(): void
    {
        $zip = new ZipArchive;
        $zip->open($this->configModel->getZipFile());

        // zip file validation
        if ($zip->numFiles === 0) {
            throw new UnparsableZipFileException('Unexpected zip file structure.');
        }

        $logLineCount = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // zip file validation
            if (substr_count($filename, '/') !== 2) {
                throw new UnparsableZipFileException('Unexpected zip file structure.');
            }

            $fp = $zip->getStream($zip->getNameIndex($i));

            // zip file validation
            if (!$fp) {
                throw new UnparsableZipFileException('Unknown error reading zip file.');
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

    /**
     * @param string $fileName
     * @param string $rawLogLine
     * @return LogLineModel
     */
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
        $channel = explode('/', $fileName)[2];
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
