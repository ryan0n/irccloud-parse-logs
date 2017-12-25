<?php

namespace ryan0n\IrcCloudParseLogs\Export;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

interface ExportDriverInterface
{
    public function export(LogLineModel $logLine) : bool;
}
