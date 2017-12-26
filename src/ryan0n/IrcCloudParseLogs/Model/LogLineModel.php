<?php

namespace ryan0n\IrcCloudParseLogs\Model;

class LogLineModel
{
    /* @var string */
    private $rawLine;
    /* @var string */
    private $dateTime;
    /* @var string */
    private $type;
    /* @var string */
    private $network;
    /* @var string */
    private $channel;
    /* @var string */
    private $nick;
    /* @var string */
    private $message;
    /* @var int */
    private $lineNumber;

    /**
     * @return string|null
     */
    public function getRawLine(): ?string
    {
        return $this->rawLine;
    }

    /**
     * @param string|null $rawLine
     */
    public function setRawLine(?string $rawLine): void
    {
        $this->rawLine = $rawLine;
    }

    /**
     * @return string|null
     */
    public function getDateTime(): ?string
    {
        return $this->dateTime;
    }

    /**
     * @param string|null $dateTime
     */
    public function setDateTime(?string $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * @param string|null $nick
     */
    public function setNick(?string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * @return string|null
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * @param string|null $channel
     */
    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getNetwork(): ?string
    {
        return $this->network;
    }

    /**
     * @param string|null $network
     */
    public function setNetwork(?string $network): void
    {
        $this->network = $network;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return int|null
     */
    public function getLineNumber(): ?int
    {
        return $this->lineNumber;
    }

    /**
     * @param int|null $lineNumber
     */
    public function setLineNumber(?int $lineNumber): void
    {
        $this->lineNumber = $lineNumber;
    }
}