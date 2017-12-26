<?php

namespace ryan0n\IrcCloudParseLogs\Model;

class ConfigModel
{
    /** @var string|null $searchPhrase */
    private $searchPhrase;
    /** @var string|null $zipFile */
    private $zipFile;
    /** @var string|null $exportDriver */
    private $exportDriver;

    /**
     * @param array $options
     */
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
     * @return string|null
     */
    public function getSearchPhrase(): ?string
    {
        return $this->searchPhrase;
    }

    /**
     * @param string|null $searchPhrase
     */
    public function setSearchPhrase(?string $searchPhrase): void
    {
        $this->searchPhrase = $searchPhrase;
    }

    /**
     * @return string
     */
    public function getZipFile(): ?string
    {
        return $this->zipFile;
    }

    /**
     * @param string $zipFile
     */
    public function setZipFile(?string $zipFile): void
    {
        $this->zipFile = $zipFile;
    }

    /**
     * @return string|null
     */
    public function getExportDriver(): ?string
    {
        return $this->exportDriver;
    }

    /**
     * @param string|null $exportDriver
     */
    public function setExportDriver(?string $exportDriver): void
    {
        $this->exportDriver = $exportDriver;
    }
}