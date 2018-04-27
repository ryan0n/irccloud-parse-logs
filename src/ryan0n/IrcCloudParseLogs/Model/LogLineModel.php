<?php

namespace ryan0n\IrcCloudParseLogs\Model;

use DateTime;

class LogLineModel
{
    /* @var string|null $fileName */
    private $fileName;
    /* @var string|null $rawLine */
    private $rawLine;
    /* @var DateTime|null $dateTime */
    private $dateTime;
    /* @var string|null $type */
    private $type;
    /* @var string $network */
    private $network;
    /* @var string $channel */
    private $channel;
    /* @var string|null $nick */
    private $nick;
    /* @var string|null $message */
    private $message;
    /* @var int|null $lineNumber */
    private $lineNumber;

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function getRawLine(): ?string
    {
        return $this->rawLine;
    }

    public function setRawLine(?string $rawLine): void
    {
        $this->rawLine = $rawLine;
    }

    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(?DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick(?string $nick): void
    {
        $this->nick = $nick;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function setNetwork(string $network): void
    {
        $this->network = $network;
    }

    public function getLineNumber(): ?int
    {
        return $this->lineNumber;
    }

    public function setLineNumber(?int $lineNumber): void
    {
        $this->lineNumber = $lineNumber;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }
}
