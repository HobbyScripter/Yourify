<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 07.04.2017
 * Time: 20:01
 */

namespace Yourify\Providers;

use Libs\ts3admin as TeamspeakService;

class Teamspeak
{
    /**
     * @var TeamspeakService
     */
    protected $ts3;
    protected $host;
    protected $query_port;
    public $server_port;
    protected $token;

    public function __construct()
    {
        $this->host = config('teamspeak.server.host', 'localhost');
        $this->query_port = config('teamspeak.server.query_port', 10011);
        $this->server_port = config('teamspeak.server.server_port', 9987);
        $this->token = config('teamspeak.server.token', "");
    }

    /**
     * @return TeamspeakService
     */

    public function connect(){
        $this->ts3 = new TeamspeakService($this->host,$this->query_port);
        $this->ts3->connect();
        return $this->ts3;
    }

    public function disconnect(){
        $this->ts3->quit();
        $this->ts3 = '';
    }

    public function client(){
        $this->checkServer();
        return $this->ts3;
    }

    public function checkServer(){
        if(!is_object($this->ts3)){
            $this->ts3 = $this->connect();
            $this->ts3->selectServer($this->server_port);
        }
    }

    public function getClients(){
        $clients = $this->ts3->clientList();
        $clients = $clients['data'];
        return $clients;
    }

    public function getServerGroup(){
        $goups = $this->ts3->serverGroupList();
        $goups = $goups['data'];
        return $goups;
    }

    public function kickUser($guid){
        $this->ts3->tokenUse($this->token);
        $this->ts3->clientKick($guid);
    }

    public static function __callStatic($function, $arguments)
    {
        switch ($function){
            case "kick":
                $function = 'kickUser';
                break;
            default:
                $function = '';
        }

        if ($function !== ''){
            $ts = new Teamspeak();
        }
    }

}