<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

interface ExportDriverInterface
{
    /**
     * @param LogLineModel $logLine
     * @return bool
     */
    public function export(LogLineModel $logLine) : bool;
}
