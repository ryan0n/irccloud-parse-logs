<?php

namespace ryan0n\IRCCloudParseLogs;


class IRCCloudLogLine
{
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
        $this->dateTime = addslashes($dateTime);
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
        $this->type = addslashes($type);
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
        if (strtolower($nick) != 'joined') {
            $this->nick = addslashes($nick);
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
        $this->channel = addslashes($channel);
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
        $this->message = addslashes($message);
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
        $this->network = addslashes($network);
        return $this;
    }

    protected $dateTime;
    protected $type;
    protected $nick;
    protected $channel;
    protected $message;
    protected $network;

    protected $dbConnection;
    protected $dbTable;

    protected $numInserts;

    public function __construct($dbConnection, $dbTable)
    {
        $this->dbConnection = $dbConnection;
        $this->dbTable = $dbTable;
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