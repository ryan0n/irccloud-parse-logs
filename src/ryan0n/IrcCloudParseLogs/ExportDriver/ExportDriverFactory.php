<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

class ExportDriverFactory
{
    public static function factory($type): ExportDriverInterface
    {
        switch (strtolower($type)) {
            case 'genericoutput':
                return new GenericOutput();
                break;
            case 'json':
                return new Json();
                break;
            case 'mysql':
                return new MySQL();
                break;
            default:
                throw new \InvalidArgumentException("Invalid export driver type '{$type}'.");
                break;
        }
    }
}