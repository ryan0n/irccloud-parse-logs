<?php

namespace ryan0n\IRCCloudParseLogs\DTO;


class IRCCloudLogLine
{

    protected $rawLine;

    protected $dateTime;
    protected $type;
    protected $network;
    protected $channel;
    protected $nick;
    protected $message;


    /**
     * @return mixed
     */
    public function getRawLine()
    {
        return $this->rawLine;
    }

    /**
     * @param mixed $rawLine
     */
    public function setRawLine($rawLine)
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
    public function setDateTime($dateTime)
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
    public function setType($type)
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
    public function setNick($nick)
    {
        if (strtolower($nick) !== 'joined') {
            $this->nick = $nick;
        }
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
    public function setChannel($channel)
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
    public function setMessage($message)
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
    public function setNetwork($network)
    {
        $this->network = $network;
        return $this;
    }

    public function persist()
    {


        // TODO: FIX THIS GARBAGE
        /*
        $sql = "
          INSERT IGNORE INTO ".$this->dbTable .
          "VALUES (null,'{$this->network}', '{$this->dateTime}', '{$this->type}', '"
          . "{$this->nick}','{$this->channel}','{$this->message}')";
        $sha1 = substr(sha1($sql), 0, 20);
        $sql = "INSERT IGNORE INTO ".$this->dbTable." VALUES (null,'{$this->network}','{$this->dateTime}','{$this->type}','{$this->nick}','{$this->channel}','{$this->message}','{$sha1}')";
        $this->dbConnection->query($sql);

        return;

        return [
            'network' =>  $this->network,
            'dateTime' => $this->dateTime,
            'type' => $this->type,
            'nick' => $this->nick,
            'channel' => $this->channel,
            'message' => $this->message,
        ];
        */
    }
}