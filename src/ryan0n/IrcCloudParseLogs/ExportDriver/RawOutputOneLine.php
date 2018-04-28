<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

class RawOutputOneLine implements ExportDriverInterface
{
    public function export(LogLineModel $logLine) : bool
    {
        echo $logLine->getChannel() . ' - ' . $logLine->getRawLine() . "\n";
        return true;
    }
}
