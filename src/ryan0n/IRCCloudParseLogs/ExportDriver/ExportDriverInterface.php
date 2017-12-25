<?php

namespace ryan0n\IRCCloudParseLogs\ExportDriver;

use ryan0n\IRCCloudParseLogs\DTO\LogLine;

interface ExportDriverInterface
{
    public function export(LogLine $logLine);
}
