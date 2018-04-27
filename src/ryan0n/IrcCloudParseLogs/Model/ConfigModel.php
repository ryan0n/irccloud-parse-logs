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
    /** @var int|null $contextLines */
    private $contextLines;

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
                case 'contextlines':
                    $this->contextLines = strtolower($value);
                    break;
            }
        }
        if (empty($this->exportDriver)) {
            $this->exportDriver = 'genericoutput';
        }
        if (empty($this->contextLines)) {
            $this->contextLines = 0;
        }
    }

    public function getSearchPhrase(): ?string
    {
        return $this->searchPhrase;
    }

    public function setSearchPhrase(?string $searchPhrase): void
    {
        $this->searchPhrase = $searchPhrase;
    }

    public function getZipFile(): ?string
    {
        return $this->zipFile;
    }

    public function setZipFile(?string $zipFile): void
    {
        $this->zipFile = $zipFile;
    }

    public function getExportDriver(): ?string
    {
        return $this->exportDriver;
    }

    public function setExportDriver(?string $exportDriver): void
    {
        $this->exportDriver = $exportDriver;
    }

    public function getContextLines(): ?int
    {
        return $this->contextLines;
    }

    public function setContextLines(?int $contextLines): void
    {
        $this->contextLines = $contextLines;
    }
}
