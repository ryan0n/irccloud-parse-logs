<?php

namespace ryan0n\IRCCloudParseLogs;

use \mysqli;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;

class IRCCloudParseLogs
{

    protected $files;
    protected $dbConnection;
    protected $dbTable;
    protected $currentNetwork;

    public function __construct()
    {
        global $argv;
        $filePath = $argv[1];
        $this->dbConnection = new mysqli('localhost', 'nobody', '', 'test');
        $this->dbTable = "irccloud";
        #$this->dbConnection->query("TRUNCATE TABLE irccloud");
        $this->files = $this->getFiles($filePath);
    }

    private function getFiles($path)
    {
        $objects = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($objects as $name => $object) {
            if ($object->getExtension()=="txt") {
                $this->parseLogFile($name, $object);
                #echo "\n";
            }

        }
    }

    private function getNetwork($object)
    {
        $network = $object->getPathname();
        $pos = strpos($network, $object->getFilename());
        $network = substr($network, 0, $pos-1);
        $network = strrev($network);
        $pos = strpos($network, '/');
        $network = strrev(substr($network, 0, $pos));

        return $network;
    }

    private function parseLogFile($filePath, $object)
    {
        $file = file($filePath);

        $total_lines = count($file) - 1;
        $current_lines=0;
        $newlinesent = false;

        $network = $this->getNetwork($object);
        $channel = str_replace(".txt", "", $object->getFilename());


        foreach ($file as $line) {
            $logLine = new IRCCloudLogLine($this->dbConnection, $this->dbTable);
            $logLine->setNetwork($network);
            $logLine->setChannel($channel);

            $line = explode(" ", $line);
            $logLine->setDateTime(
                substr(implode(' ', [$line[0], $line[1]]), 1, strlen(implode(' ', [$line[0], $line[1]])) - 2)
            );
            unset($line[0], $line[1]);

            if ($line[2][0] == '<') {
                $logLine->setType('message');
                $logLine->setNick(substr($line[2], 1, strlen($line[2]) - 2));
                unset($line[2]);
                $logLine->setMessage(implode(' ', $line));
            } elseif (trim($line[2]) == '—') {
                $logLine->setType('message');
                $logLine->setNick(substr($line[3], 1, strlen($line[3]) - 2));
                unset($line[2], $line[3]);
                $logLine->setMessage(implode(' ', $line));
            } else {
                switch ($line[2]) {
                    case "→":
                        $logLine->setType('joined');
                        $logLine->setNick($line[3]);
                        $logLine->setMessage($line[5]);
                        break;
                    case "←":
                    case "⇐":
                        if ($line[6] != 'channel:') {
                            $logLine->setType('parted');
                            $logLine->setNick($line[3]);
                            $logLine->setMessage($line[5]);
                        }
                        break;
                    default:
                        $logLine->setType('other');
                        #echo "\n".$line[5][0]."\n";
                        #$logLine->setNick('');
                        $logLine->setMessage(implode(" ", $line));
                        break;
                }
            }
            if ($logLine->getType()) {
                $current_lines++;
                if ($newlinesent == false) {
                    $newlinesent = true;
                    $this->show_status($current_lines, $total_lines, $logLine, true);
                } else {
                    if ($current_lines % 5000 == 0 || $current_lines == $total_lines) {
                        $this->show_status($current_lines, $total_lines, $logLine);
                    }
                }
                $logLine->persist();

            }


        }
    }

    private function showStatus($done, $total, $logLine, $reset = false, $size = 30)
    {


        static $start_time;

        // if we go over our bound, just ignore it
        if ($done > $total) {
            return;
        }

        if (empty($start_time) || $reset) {
            echo "\n";
            $start_time=time();
        }
        $now = time();

        $perc=(double)($done/$total);

        $bar=floor($perc*$size);

        $status_bar="\r[";
        $status_bar.=str_repeat("=", $bar);
        if ($bar<$size) {
            $status_bar.=">";
            $status_bar.=str_repeat(" ", $size-$bar);
        } else {
            $status_bar.="=";
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="] $disp%  ".number_format($done)."/".number_format($total);

        $rate = ($now-$start_time)/$done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar.= " Remaining: ".number_format($eta)." sec, Elapsed: ".number_format($elapsed)." sec, Network: " . $logLine->getNetwork() . ", Channel: ".$logLine->getChannel();

        echo "$status_bar  ";

        flush();

        // when done, send a newline
        if ($done == $total) {
            #echo "\n";
        }
    }

    public function run()
    {
    }

}