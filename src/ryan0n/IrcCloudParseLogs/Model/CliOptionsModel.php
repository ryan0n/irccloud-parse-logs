<?php

namespace ryan0n\IrcCloudParseLogs\Model;

class CliOptionsModel
{
    /** @var string $searchPhrase */
    private $searchPhrase;

    /** @var string $zipFile */
    private $zipFile;

    /** @var string $exportDriver */
    private $exportDriver;

    public function __construct(array $options)
    {
        foreach ($options as $option => $value) {
            switch (strtolower($option)) {
                case 'zipfile':
                    // zipFile needs to be case sensitive. the rest can be case insensitive.
                    $this->zipFile = $value;
                    break;
                case 'searchphrase':
                    $this->searchPhrase = strtolower($value);
                    break;
                case 'exportdriver':
                    $this->exportDriver = strtolower($value);
                    break;
            }
        }
        if (empty($this->exportDriver)) {
            $this->exportDriver = 'genericoutput';
        }
    }

    /**
     * @return string
     */
    public function getSearchPhrase()
    {
        return $this->searchPhrase;
    }

    /**
     * @param string $searchPhrase
     */
    public function setSearchPhrase($searchPhrase)
    {
        $this->searchPhrase = $searchPhrase;
    }

    /**
     * @return string
     */
    public function getZipFile()
    {
        return $this->zipFile;
    }

    /**
     * @param string $zipFile
     */
    public function setZipFile($zipFile)
    {
        $this->zipFile = $zipFile;
    }

    /**
     * @return string
     */
    public function getExportDriver()
    {
        return $this->exportDriver;
    }

    /**
     * @param string $exportDriver
     */
    public function setExportDriver($exportDriver)
    {
        $this->exportDriver = $exportDriver;
    }
}