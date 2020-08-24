<?php

namespace brokiem\QuickBoard;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;

use brokiem\QuickBoard\libs\libpmquery\PMQuery;
use brokiem\QuickBoard\libs\libpmquery\PmQueryException;

class QBListener implements Listener {
    
	private $plugin;
	
   public function __construct(MainBoard $plugin)
    {
        $this->plugin = $plugin;
    }
    
    public function Holders(Player $player, string $holder): string
    {
	$level = $player->getLevel();
        if($this->plugin->getConfig()->get("enable") === true){
	    try{
		$server = PMQuery::query($this->plugin->getConfig()->get("ip"), ($this->plugin->getConfig()->get("port")));
	        $total = $server['Players'];
  	    }catch(PmQueryException $e){
		$total = "§cOFFLINE";
	    }
	    }elseif($this->plugin->getConfig()->get("enable") === false){
			$total = "§cDisabled";
	    }
        $holder = str_replace("%name%", $player->getName(), $holder);
        $holder = str_replace("%display_name%", $player->getDisplayName(), $holder);
        $holder = str_replace("%name%", $player->getName(), $holder);
        $holder = str_replace("%server_online%", count($player->getServer()->getOnlinePlayers()), $holder);
        $holder = str_replace("%max_online%", $player->getServer()->getMaxPlayers(), $holder);
        $holder = str_replace("%server_tps%", $player->getServer()->getTicksPerSecond(), $holder);
        $holder = str_replace("%player_ping%", $player->getPing(), $holder);
        $holder = str_replace("%server_load%", $player->getServer()->getTickUsage(), $holder);
	$holder = str_replace("%server_query%", $total, $holder);
        $holder = str_replace("%item_id%", $player->getInventory()->getItemInHand()->getId(), $holder);
	    $holder = str_replace("%player_ip%", $player->getAddress(), $holder);
	    $holder = str_replace("%player_x%", $player->getFloorX(), $holder);
	    $holder = str_replace("%player_y%", $player->getFloorY(), $holder);
	    $holder = str_replace("%player_z%", $player->getFloorZ(), $holder);
	    $holder = str_replace("%player_world%", $level->getFolderName(), $holder);
	    $holder = str_replace("%world_player_count%", count($level->getPlayers()), $holder);
	    $holder = str_replace("%date%", date("H:i a"), $holder);
		return ((string) $holder);
    }

}
