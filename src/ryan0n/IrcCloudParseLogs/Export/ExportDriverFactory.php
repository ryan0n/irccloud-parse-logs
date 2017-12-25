<?php

namespace ryan0n\IrcCloudParseLogs\Export;

class ExportDriverFactory
{
    public static function factory($type): ExportDriverInterface
    {
        switch ($type) {
            case 'GenericOutput':
                return new GenericOutput();
                break;
            case 'Json':
                return new Json();
                break;
            case 'MySQL':
                return new MySQL();
                break;
            default:
                throw new \InvalidArgumentException("Invalid export driver type '{$type}'.");
                break;
        }
    }
}