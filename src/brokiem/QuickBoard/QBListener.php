<?php

namespace brokiem\QuickBoard;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

use brokiem\QuickBoard\libs\libpmquery\PMQuery;
use brokiem\QuickBoard\libs\libpmquery\PmQueryException;

class QBListener implements Listener {
    
	private $plugin;
	
   public function __construct(MainBoard $plugin)
    {
        $this->plugin = $plugin;
    }
    
    public function Holders(Player $player)
    {
        if($this->plugin->getConfig()->get("enable") === true){
		try{
		    $server = PMQuery::query($this->plugin->getConfig()->get("ip"), ($this->plugin->getConfig()->get("port")));
	            $total = $server['Players'];
	            Server::getInstance()->getLogger()->info("QuickBoard> There are ".$players." on the queried server right now!");
		}catch(PmQueryException $e){
		    $total = "§cOFFLINE";
		    Server::getInstance()->getLogger()->info("QuickBoard> The queried server is offline right now!");
		} 
		}
        
        $config = $this->getConfig()->get("quickboard-lines");
        $holder = str_replace("%name%", $player->getName(), $config);
        $holder = str_replace("%display_name%", $player->getDisplayName(), $config);
        $holder = str_replace("%name%", $player->getName(), $config);
        $holder = str_replace("%server_online%", count($player->getServer()->getOnlinePlayers()), $config);
        $holder = str_replace("%max_online%", $player->getServer()->getMaxPlayers(), $config);
        $holder = str_replace("%server_tps%", $player->getServer()->getTicksPerSecond(), $config);
        $holder = str_replace("%player_ping%", $player->getPing(), $config);
        $holder = str_replace("%server_load%", $player->getServer()->getTickUsage(), $config);
        $holder = str_replace("%server_query%", $total, $config);
        // more holders soon :D
     }

}
