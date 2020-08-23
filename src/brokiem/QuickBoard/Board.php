<?php

namespace brokiem\QuickBoard;

use pocketmine\scheduler\Task;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;
use brokiem\QuickBoard\libs\libpmquery\PMQuery;
use brokiem\QuickBoard\libs\libpmquery\PmQueryException;
use brokiem\QuickBoard\libs\Scoreboards\Scoreboards;

class Board extends PluginTask {

    public function __construct(MainBoard $plugin) : void
    {
        $this->plugin = $plugin;
    }
    
    public function onRun(int $tick) : void
    {
        $main = $this->plugin;
      
        foreach ($this->getPlayers() as $p) {
                    $api = Scoreboards::getInstance();
                    $api->new($p, "Title", "§l§bLOBBY");
                    $api->setLine($p, 1, " ");
                    $api->setLine($p, 2, "§7Hi, §3". $p->getName());
                    $api->setLine($p, 3, "§7Coin: §3". EconomyAPI::getInstance()->myMoney($p));
                    $api->setLine($p, 4, "    ");
                    $api->setLine($p, 5, "§7Lobby §3#1");
                    $api->setLine($p, 6, "§7Online: §3". $this->plugin->getServer()->getOnlinePlayers());
                    $api->setLine($p, 7, "      ");
                    $api->setLine($p, 8, "§3kawaismp.net");
                    $api->getObjectiveName($p);
        }
    }
}
