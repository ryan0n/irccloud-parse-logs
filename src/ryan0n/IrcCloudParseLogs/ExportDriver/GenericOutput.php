<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

class GenericOutput implements ExportDriverInterface
{
    public function export(LogLineModel $logLine) : bool
    {
        $output =  "\n--------------------------";
        $output .= "\nlineNumber: " . number_format($logLine->getLineNumber());
        $output .= "\ndateTime:   {$logLine->getDateTime()->format('Y-m-d H:i:s')}";
        $output .= "\ntype:       {$logLine->getType()}";
        $output .= "\nnetwork:    {$logLine->getNetwork()}";
        $output .= "\nchannel:    {$logLine->getChannel()}";
        $output .= "\nnick:       {$logLine->getNick()}";
        $output .= "\nmessage:    {$logLine->getMessage()}";
        $output .= "\nraw:        {$logLine->getRawLine()}";
        $output .= "\n";
        echo $output;
        return true;
    }
}
