<?php

ini_set('memory_limit', '-1');

class IRCCloudLogParser
{

    protected $files;
    protected $dbConnection;
    protected $dbTable;
    protected $currentNetwork;

    function __construct()
    {
        global $argv;
        $filePath = $argv[1];
        $this->dbConnection = new mysqli('localhost', 'nobody', '', 'test');
        $this->dbTable = "irccloud";
        #$this->dbConnection->query("TRUNCATE TABLE irccloud");
        $this->files = $this->getFiles($filePath);

    }

    private function getFiles($path)
    {
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object){
            if ($object->getExtension()=="txt") {
                $this->parseLogFile($name, $object);
                #echo "\n";
            }

        }
    }

    private function getNetwork($object)
    {
        $network = $object->getPathname();
        $pos = strpos($network, $object->getFilename());
        $network = substr($network, 0, $pos-1);
        $network = strrev($network);
        $pos = strpos($network, '/');
        $network = strrev(substr($network, 0, $pos));

        return $network;
    }

    private function parseLogFile($filePath, $object)
    {
        $file = file($filePath);

        $total_lines = count($file) - 1;
        $current_lines=0;
        $newlinesent = false;
        $newlinesent = false;

        $network = $this->getNetwork($object);
        $channel = str_replace(".txt", "", $object->getFilename());


        foreach($file as $line) {
            $logLine = new logLine($this->dbConnection, $this->dbTable);
            $logLine->setNetwork($network);
            $logLine->setChannel($channel);

            $line = explode(" ", $line);
            $logLine->setDateTime(substr(implode(' ', [$line[0], $line[1]]), 1, strlen(implode(' ', [$line[0], $line[1]])) - 2));
            unset($line[0], $line[1]);

            if ($line[2][0] == '<') {
                $logLine->setType('message');
                $logLine->setNick(substr($line[2], 1, strlen($line[2]) - 2));
                unset($line[2]);
                $logLine->setMessage(implode(' ', $line));
            } elseif (trim($line[2]) == '—') {
                $logLine->setType('message');
                $logLine->setNick(substr($line[3], 1, strlen($line[3]) - 2));
                unset($line[2], $line[3]);
                $logLine->setMessage(implode(' ', $line));
            } else {
                switch ($line[2]) {
                    case "→":
                        $logLine->setType('joined');
                        $logLine->setNick($line[3]);
                        $logLine->setMessage($line[5]);
                        break;
                    case "←":
                    case "⇐":
                        if ($line[6] != 'channel:') {
                            $logLine->setType('parted');
                            $logLine->setNick($line[3]);
                            $logLine->setMessage($line[5]);
                        }
                        break;
                    default:
                        $logLine->setType('other');
                        #echo "\n".$line[5][0]."\n";
                        #$logLine->setNick('');
                        $logLine->setMessage(implode(" ", $line));
                        break;
                }
            }
            if ($logLine->getType()) {
                $current_lines++;
                if ($newlinesent == false) {
                    $newlinesent = true;
                    $this->show_status($current_lines, $total_lines, $logLine, true);
                } else {
                    if($current_lines % 5000 == 0 || $current_lines == $total_lines) {
                        $this->show_status($current_lines, $total_lines, $logLine);
                    }
                }
                $logLine->persist();

            }


        }

    }

    private function show_status($done, $total, $logLine, $reset=false, $size=30) {


        static $start_time;

        // if we go over our bound, just ignore it
        if($done > $total) return;

        if(empty($start_time) || $reset) {
            echo "\n";
            $start_time=time();
        }
        $now = time();

        $perc=(double)($done/$total);

        $bar=floor($perc*$size);

        $status_bar="\r[";
        $status_bar.=str_repeat("=", $bar);
        if($bar<$size){
            $status_bar.=">";
            $status_bar.=str_repeat(" ", $size-$bar);
        } else {
            $status_bar.="=";
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="] $disp%  ".number_format($done)."/".number_format($total);

        $rate = ($now-$start_time)/$done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar.= " Remaining: ".number_format($eta)." sec, Elapsed: ".number_format($elapsed)." sec, Network: " . $logLine->getNetwork() . ", Channel: ".$logLine->getChannel();

        echo "$status_bar  ";

        flush();

        // when done, send a newline
        if($done == $total) {
            #echo "\n";
        }

    }

    public function run()
    {

    }


}

class logLine
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
     * @return logLine
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
     * @return logLine
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
     * @return logLine
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
     * @return logLine
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
     * @return logLine
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
     * @return logLine
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

    var $numInserts;

    public function __construct($dbConnection, $dbTable)
    {
        $this->dbConnection = $dbConnection;
        $this->dbTable = $dbTable;
    }

    public function persist() {

        $sql = "INSERT IGNORE INTO ".$this->dbTable." VALUES (null,'{$this->network}','{$this->dateTime}','{$this->type}','{$this->nick}','{$this->channel}','{$this->message}')";
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
    }

}

$objParser = new IRCCloudLogParser();
$objParser->run();



?>