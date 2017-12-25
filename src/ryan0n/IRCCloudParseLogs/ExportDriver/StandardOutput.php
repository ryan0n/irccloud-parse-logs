<?php

namespace ryan0n\IRCCloudParseLogs\ExportDriver;

use ryan0n\IRCCloudParseLogs\DTO\LogLine;

class StandardOutput implements ExportDriverInterface
{
    public function export(LogLine $logLine)
    {
        if (false !== stripos($logLine->getRawLine(), ' search phrase here')) {
            print_r($logLine);
        }
    }
}