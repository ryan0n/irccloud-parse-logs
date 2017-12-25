<?php

namespace ryan0n\IrcCloudParseLogs\Export;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

interface ExportInterface
{
    public function export(LogLineModel $logLine);
}
