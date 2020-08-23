<?php

namespace brokiem\QuickBoard;

use pocketmine\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\Listener;
use pocketmine\utils\Config;

class QBListener extends PluginBase implements Listener {
    
   public function __construct(MainBoard $plugin)
    {
        $this->plugin = $plugin;
    }
    
    public function Holders(Player $player)
    {
        $config = $this->getConfig()->get("quickboard-lines");
        $holder = str_replace("%name%", $player->getName(), $config);
        $holder = str_replace("%display_name%", $player->getDisplayName(), $config);
        $holder = str_replace("%name%", $player->getName(), $config);
        $holder = str_replace("%server_online%", count($player->getServer()->getOnlinePlayers()), $config);
        $holder = str_replace("%max_online%", $player->getServer()->getMaxPlayers(), $config);
        $holder = str_replace("%server_tps%", $player->getServer()->getTicksPerSecond(), $config);
        $holder = str_replace("%player_ping%", $player->getPing(), $config);
        $holder = str_replace("%server_load%", $player->getServer()->getTickUsage(), $config);
        // more holders soon :D
     }
}
