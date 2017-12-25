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
    public function getRawLine()
    {
        return $this->rawLine;
    }

    /**
     * @param string|null $rawLine
     * @return LogLineModel
     */
    public function setRawLine($rawLine): LogLineModel
    {
        $this->rawLine = $rawLine;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param string|null $dateTime
     * @return LogLineModel
     */
    public function setDateTime($dateTime): LogLineModel
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return LogLineModel
     */
    public function setType($type): LogLineModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param string|null $nick
     * @return LogLineModel
     */
    public function setNick($nick): LogLineModel
    {
        $this->nick = $nick;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string|null $channel
     * @return LogLineModel
     */
    public function setChannel($channel): LogLineModel
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return LogLineModel
     */
    public function setMessage($message): LogLineModel
    {
        $message = str_replace("\r\n", '', $message);
        $this->message = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param string|null $network
     * @return LogLineModel
     */
    public function setNetwork($network): LogLineModel
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