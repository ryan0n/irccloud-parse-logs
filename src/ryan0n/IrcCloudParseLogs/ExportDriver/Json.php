<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

class Json implements ExportDriverInterface
{
    /**
     * @param LogLineModel $logLine
     * @return bool
     */
    public function export(LogLineModel $logLine) : bool
    {
        echo json_encode($logLine->toArray()) . "\n";
        return true;
    }
}