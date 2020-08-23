<?php

namespace brokiem\QuickBoard;

use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\Server;
use onebone\economyapi\EconomyAPI;
use brokiem\QuickBoard\libs\libpmquery\PMQuery;
use brokiem\QuickBoard\libs\libpmquery\PmQueryException;
use pocketmine\utils\Config;

class BoardTask extends Task {

    public function __construct(MainBoard $plugin)
    {
        $this->plugin = $plugin;
    }
    
    public function onRun(int $tick)
    {
        $main = $this->plugin;
      
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
		try{
		    $server = PMQuery::query($this->plugin->getConfig()->get("ip"), ($this->plugin->getConfig()->get("port")));
	            $total = $server['Players'];
	            Server::getInstance()->getLogger()->info("QuickBoard> There are ".$players." on the queried server right now!");
		}catch(PmQueryException $e){
		    $total = "§cOFFLINE";
		    Server::getInstance()->getLogger()->info("QuickBoard> The queried server is offline right now!");
		} 
		
                $main->new($p, "Title", ($this->plugin->getConfig()->get("quickboard-title")));
                $c = 0;
                foreach($this->plugin->getConfig()->get("quickboard-lines") as $lines ){
                    $c++;
                    if($c <= 15){
                        $main->setLine($p, $c, $lines);
                    }
                }
                $main->getObjectiveName($p);
        }
    }
}
