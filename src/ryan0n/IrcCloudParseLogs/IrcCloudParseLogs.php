<?php

namespace ryan0n\IrcCloudParseLogs;

ini_set("memory_limit", "8G");

use ryan0n\IrcCloudParseLogs\Exception\ExportDriverNotFoundException;
use ryan0n\IrcCloudParseLogs\Exception\UnparsableZipFileException;
use ryan0n\IrcCloudParseLogs\ExportDriver\ExportDriverInterface;
use ryan0n\IrcCloudParseLogs\ExportDriver\RawOutput;
use ryan0n\IrcCloudParseLogs\ExportDriver\Json;
use ryan0n\IrcCloudParseLogs\ExportDriver\MySQL;
use ryan0n\IrcCloudParseLogs\ExportDriver\RawOutputOneLine;
use ryan0n\IrcCloudParseLogs\Model\ConfigModel;
use ryan0n\IrcCloudParseLogs\Model\LogLineModel;
use ZipArchive;

class IrcCloudParseLogs
{
    /** @var ConfigModel $configModel */
    protected $configModel;

    /** @var ExportDriverInterface $exportDriver */
    protected $exportDriver;

    /** @var int $uncompressedZipSizeTotal */
    protected $uncompressedZipSizeTotal;

    /** @var int $uncompressedZizSizeProgress */
    protected $uncompressedZizSizeProgress;

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
            case 'rawoutput':
                return new RawOutput();
                break;
            case 'rawoutputoneline':
                return new RawOutputOneLine();
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
        $uncompressedZipSizeTotal = $this->getZipOriginalSize($this->configModel->getZipFile());

        $zip = new ZipArchive;
        $zip->open($this->configModel->getZipFile());

        if ($zip->numFiles === 0) {
            throw new UnparsableZipFileException('Unexpected zip file structure.');
        }

        $logLineCurrent = 0;
        $logLineModelsQueue = [];
        $logLineBeginExport = null;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (substr_count($filename, '/') !== 2) {
                throw new UnparsableZipFileException('Unexpected zip file structure.');
            }
            $contents = file_get_contents("zip://{$this->configModel->getZipFile()}#{$zip->getNameIndex($i)}");

            $shouldProcessFile = false;
            if (!$this->configModel->getSearchPhrase()) {
                // Not in search mode. Processing all lines.
                $shouldProcessFile = true;
            } elseif (false !== strpos($contents, $this->configModel->getSearchPhrase())) {
                // In search mode. Boost performance by not processing lines that don't contain search phrase.
                $shouldProcessFile = true;
            }

            if (!$shouldProcessFile) {
                $this->uncompressedZizSizeProgress += mb_strlen($contents, '8bit');
                $this->showStatus($this->uncompressedZizSizeProgress, $uncompressedZipSizeTotal);
            } else {
                $rawLine = strtok($contents, "\n");
                while ($rawLine !== false) {

                    $this->uncompressedZizSizeProgress += mb_strlen($rawLine, '8bit');
                    if ($logLineCurrent % 30000 === 0) {
                        $this->showStatus($this->uncompressedZizSizeProgress, $uncompressedZipSizeTotal);
                    }

                    $logLineCurrent++;

                    if (null !== $this->configModel->getContextLines()) {
                        $logLineModel = new LogLineModel();
                        $logLineModel->setFileName($filename);
                        $logLineModel->setRawLine($rawLine);
                        $logLineModel->setLineNumber($logLineCurrent);
                        $logLineModelsQueue[] = $logLineModel;

                        $sliceBegin = \count($logLineModelsQueue) - $this->configModel->getContextLines();
                        $logLineModelsQueue = \array_slice(
                            $logLineModelsQueue,
                            ($sliceBegin <= 0) ? 0 : $sliceBegin,
                            \count($logLineModelsQueue)
                        );
                    }

                    if (!$this->configModel->getSearchPhrase()) {
                        // Not in search mode. Processing all lines.
                        $logLineBeginExport = $logLineCurrent;
                    } elseif (false !== strpos($rawLine, $this->configModel->getSearchPhrase())) {
                        // In search mode. Boost performance by not processing lines that don't contain search phrase.
                        if ($this->configModel->getContextLines()) {
                            if (null === $logLineBeginExport) {
                                $logLineBeginExport = $logLineCurrent + (int) ceil($this->configModel->getContextLines() / 2);
                            }
                        } else {
                            $logLineBeginExport = $logLineCurrent;
                        }
                    }

                    if ($logLineCurrent === $logLineBeginExport) {
                        $logLineModel = new LogLineModel();
                        $logLineModel->setFileName($filename);
                        $logLineModel->setRawLine($rawLine);
                        $logLineModel->setLineNumber($logLineCurrent);
                        $logLineModelsQueue[] = $logLineModel;

                        foreach ($logLineModelsQueue as $logLineModel) {
                            $logLineModel = $this->populateLogLineModel($logLineModel);
                            if (null === $this->configModel->getDate()) {
                                $this->exportDriver->export($logLineModel);
                            } else {
                                if (strpos(
                                    $logLineModel->getDateTime()->format('Y-m-d'),
                                    $this->configModel->getDate()) !== false
                                ) {
                                    $this->exportDriver->export($logLineModel);
                                }
                            }
                        }

                        $logLineBeginExport = null;
                        $logLineModelsQueue = [];
                    }
                    $rawLine = strtok("\n");
                }
            }
        }
    }

    private function populateLogLineModel(LogLineModel $logLineModel): LogLineModel
    {
        // Clean up rawLine
        $logLineModel->setRawLine(
            rtrim($logLineModel->getRawLine(), "\r\n")
        );

        // set the network
        $network = explode('/', $logLineModel->getFileName())[1];
        $network = substr($network, strpos($network, '-') + 1, \strlen($network));
        $logLineModel->setNetwork($network);

        // set the channel
        $channel = explode('/', $logLineModel->getFileName())[2];
        $channel = str_replace('.txt', '', $channel);
        $logLineModel->setChannel($channel);

        // set the date
        $line = explode(' ', $logLineModel->getRawLine());
        try {
            $logLineModel->setDateTime(new \DateTime(substr($line[0] . ' ' . $line[1], 1, -1)));
        } catch (\Throwable $e) {
            $logLineModel->setDateTime(new \DateTime());
            return $logLineModel;
        }
        unset($line[0], $line[1]);

        // set the rest
        if ($line[2][0] === '<') {
            $logLineModel->setType('message');
            $logLineModel->setNick(substr($line[2], 1, -2));
            unset($line[2]);
            $logLineModel->setMessage(implode(' ', $line));
        } elseif (trim($line[2]) === '—') {
            $logLineModel->setType('message');
            $logLineModel->setNick(substr($line[3], 1, -2));
            unset($line[2], $line[3]);
            $logLineModel->setMessage(implode(' ', $line));
        } else {
            switch ($line[2]) {
                case '→':
                    $logLineModel->setType('joined');
                    $logLineModel->setNick($line[3]);
                    break;
                case '←':
                case '⇐':
                    if ($line[6] !== 'channel:') {
                        $logLineModel->setType('parted');
                        $logLineModel->setNick($line[3]);
                    }
                    break;
                default:
                    $logLineModel->setType('other');
                    $logLineModel->setMessage(implode(' ', $line));
                    break;
            }
        }

        return $logLineModel;
    }

    private function getZipOriginalSize(string $filename): int
    {
        $size = 0;
        $resource = zip_open($filename);
        while ($dir_resource = zip_read($resource)) {
            $size += zip_entry_filesize($dir_resource);
        }
        zip_close($resource);

        return $size;
    }

    protected function showStatus($done, $total, $size=30) {

        static $start_time;

        // if we go over our bound, just ignore it
        if ($done > $total) {
            return;
        }

        if( empty($start_time)) {
            $start_time=time();
        }
        $now = time();

        $perc = (double)($done/$total);

        $bar = floor($perc*$size);

        $status_bar = "\r[";
        $status_bar .= str_repeat("=", $bar);
        if($bar<$size){
            $status_bar .= ">";
            $status_bar .= str_repeat(" ", $size-$bar);
        } else {
            $status_bar .= "=";
        }

        $disp = number_format($perc*100, 0, '.', ',');

        $status_bar .= "] $disp%  ".number_format($done)."/".number_format($total) . " bytes";

        $rate = ($now - $start_time) / $done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

        echo "$status_bar  ";

        flush();

        // when done, send a newline
        if ($done === $total) {
            echo "\n";
        }

    }
}
