<?php

namespace ryan0n\IRCCloudParseLogs\DTO;


class LogLine
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
     * @return LogLine
     */
    public function setRawLine($rawLine): LogLine
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
     * @return LogLine
     */
    public function setDateTime($dateTime): LogLine
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
     * @return LogLine
     */
    public function setType($type): LogLine
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
     * @return LogLine
     */
    public function setNick($nick): LogLine
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
     * @return LogLine
     */
    public function setChannel($channel): LogLine
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
     * @return LogLine
     */
    public function setMessage($message): LogLine
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
     * @return LogLine
     */
    public function setNetwork($network): LogLine
    {
        $this->network = $network;
        return $this;
    }
}