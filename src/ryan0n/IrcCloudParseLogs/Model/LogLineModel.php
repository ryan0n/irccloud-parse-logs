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

    /**
     * @return string|null
     */
    public function getRawLine(): ?string
    {
        return $this->rawLine;
    }

    /**
     * @param string|null $rawLine
     * @return LogLineModel
     */
    public function setRawLine(?string $rawLine): LogLineModel
    {
        $this->rawLine = $rawLine;
        return $this;
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
     * @return LogLineModel
     */
    public function setDateTime(?string $dateTime): LogLineModel
    {
        $this->dateTime = $dateTime;
        return $this;
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
     * @return LogLineModel
     */
    public function setType(?string $type): LogLineModel
    {
        $this->type = $type;
        return $this;
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
     * @return LogLineModel
     */
    public function setNick(?string $nick): LogLineModel
    {
        $this->nick = $nick;
        return $this;
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
     * @return LogLineModel
     */
    public function setChannel(?string $channel): LogLineModel
    {
        $this->channel = $channel;
        return $this;
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
     * @return LogLineModel
     */
    public function setMessage(?string $message): LogLineModel
    {
        $this->message = $message;
        return $this;
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
     * @return LogLineModel
     */
    public function setNetwork(?string $network): LogLineModel
    {
        $this->network = $network;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}