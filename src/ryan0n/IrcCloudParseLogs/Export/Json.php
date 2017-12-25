<?php

namespace ryan0n\IrcCloudParseLogs\Export;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

class Json implements ExportDriverInterface
{
    public function export(LogLineModel $logLine) : bool
    {
        echo json_encode($logLine->toArray()) . "\n";
        return true;
    }
}