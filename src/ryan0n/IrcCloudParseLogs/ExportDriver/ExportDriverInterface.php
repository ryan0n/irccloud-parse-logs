<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

use ryan0n\IrcCloudParseLogs\Exception\ExportDriverException;
use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

interface ExportDriverInterface
{
    /**
     * @param LogLineModel $logLine
     * @throws ExportDriverException
     * @return bool
     */
    public function export(LogLineModel $logLine) : bool;
}
