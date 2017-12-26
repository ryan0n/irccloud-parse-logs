<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

interface ExportDriverInterface
{
    public function export(LogLineModel $logLine) : bool;
}
