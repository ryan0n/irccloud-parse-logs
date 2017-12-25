<?php

namespace ryan0n\IRCCloudParseLogs\ExportDriver;

use ryan0n\IRCCloudParseLogs\DTO\LogLine;

class StandardOutput implements ExportDriverInterface
{
    public function export(LogLine $logLine): bool
    {
        if (false === stripos($logLine->getRawLine(), 'f ')) {
            return false;
        }

        $output =  "\n--------------------------";
        $output .= "\ndateTime: {$logLine->getDateTime()}";
        $output .= "\nnetwork:  {$logLine->getNetwork()}";
        $output .= "\nchannel:  {$logLine->getChannel()}";
        $output .= "\nnick:     {$logLine->getNick()}";
        $output .= "\nmessage:  {$logLine->getMessage()}";
        $output .= "\nraw:      {$logLine->getRawLine()}";
        echo $output;
        return true;
    }
}