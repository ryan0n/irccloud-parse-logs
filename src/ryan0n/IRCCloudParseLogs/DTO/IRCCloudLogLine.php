<?php

namespace ryan0n\IRCCloudParseLogs\DTO;


class IRCCloudLogLine
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
     * @return IRCCloudLogLine
     */
    public function setRawLine($rawLine): IRCCloudLogLine
    {
        $this->rawLine = $rawLine;
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
     * @return IRCCloudLogLine
     */
    public function setDateTime($dateTime): IRCCloudLogLine
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
     * @return IRCCloudLogLine
     */
    public function setType($type): IRCCloudLogLine
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
     * @return IRCCloudLogLine
     */
    public function setNick($nick): IRCCloudLogLine
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
     * @return IRCCloudLogLine
     */
    public function setChannel($channel): IRCCloudLogLine
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
     * @return IRCCloudLogLine
     */
    public function setMessage($message): IRCCloudLogLine
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
     * @return IRCCloudLogLine
     */
    public function setNetwork($network): IRCCloudLogLine
    {
        $this->network = $network;
        return $this;
    }
}