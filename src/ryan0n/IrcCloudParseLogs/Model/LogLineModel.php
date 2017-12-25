<?php

namespace ryan0n\IrcCloudParseLogs\Model;

class LogLineModel
{
    private $rawLine;
    private $dateTime;
    private $type;
    private $network;
    private $channel;
    private $nick;
    private $message;

    /**
     * @return mixed
     */
    public function getRawLine()
    {
        return $this->rawLine;
    }

    /**
     * @param mixed $rawLine
     * @return LogLineModel
     */
    public function setRawLine($rawLine): LogLineModel
    {
        $this->rawLine = $rawLine;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     * @return LogLineModel
     */
    public function setDateTime($dateTime): LogLineModel
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return LogLineModel
     */
    public function setType($type): LogLineModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param mixed $nick
     * @return LogLineModel
     */
    public function setNick($nick): LogLineModel
    {
        $this->nick = $nick;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     * @return LogLineModel
     */
    public function setChannel($channel): LogLineModel
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return LogLineModel
     */
    public function setMessage($message): LogLineModel
    {
        $message = str_replace("\r\n", '', $message);
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param mixed $network
     * @return LogLineModel
     */
    public function setNetwork($network): LogLineModel
    {
        $this->network = $network;
        return $this;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}