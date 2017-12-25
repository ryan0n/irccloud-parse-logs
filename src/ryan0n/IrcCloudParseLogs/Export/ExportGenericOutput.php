<?php

namespace ryan0n\IrcCloudParseLogs\Export;

use ryan0n\IrcCloudParseLogs\Model\LogLineModel;

class ExportGenericOutput implements ExportInterface
{
    public function export(LogLineModel $logLine) : bool
    {
        // Populate $searchPhrase to only output lines with the search string present
        $searchPhrase = '';
        if (!empty($searchPhrase) && false === stripos($logLine->getRawLine(), $searchPhrase)) {
            return false;
        }

        $output =  "\n--------------------------";
        $output .= "\ndateTime: {$logLine->getDateTime()}";
        $output .= "\ntype:     {$logLine->getType()}";
        $output .= "\nnetwork:  {$logLine->getNetwork()}";
        $output .= "\nchannel:  {$logLine->getChannel()}";
        $output .= "\nnick:     {$logLine->getNick()}";
        $output .= "\nmessage:  {$logLine->getMessage()}";
        $output .= "\nraw:      {$logLine->getRawLine()}";
        echo $output;
        return true;
    }
}