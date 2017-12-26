<?php

namespace ryan0n\IrcCloudParseLogs\Model;

class LogLineModel
{
    /* @var string|null $rawLine */
    private $rawLine;
    /* @var string|null $dateTime */
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

    /**
     * @return array
     */
    public function toArray(): ?array
    {
        return get_object_vars($this);
    }

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
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
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
     * @return string
     */
    public function getNetwork(): string
    {
        return $this->network;
    }

    /**
     * @param string $network
     */
    public function setNetwork(string $network): void
    {
        $this->network = $network;
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