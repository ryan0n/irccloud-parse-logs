<?php

namespace ryan0n\IrcCloudParseLogs\ExportDriver;

use ryan0n\IrcCloudParseLogs\Exception\ExportDriverException;
use ryan0n\IrcCloudParseLogs\Model\LogLineModel;
use Exception;
use mysqli;

class MySQL implements ExportDriverInterface
{
    const DB_HOST = '';
    const DB_PORT = '';
    const DB_USERNAME = '';
    const DB_PASSWORD = '';
    const DB_DATABASE = '';

    public function export(LogLineModel $logLine) : bool
    {
        try {
            $dbConn = new mysqli(
                self::DB_HOST,
                self::DB_USERNAME,
                self::DB_PASSWORD,
                self::DB_DATABASE,
                self::DB_PORT
            );
        } catch (Exception $e) {
            throw new ExportDriverException('Could not connect to database configured in MySQL export driver.');
        }

    }
}