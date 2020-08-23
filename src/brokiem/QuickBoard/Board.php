<?php

namespace brokiem\QuickBoard;

use pocketmine\scheduler\Task;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;
use brokiem\QuickBoard\libs\libpmquery\PMQuery;
use brokiem\QuickBoard\libs\libpmquery\PmQueryException;
use pocketmine\utils\Config;

class Board extends Task {

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
				$i = $server['Players'];
			}catch(PmQueryException $e){
			} 
			//foreach ($this->plugin->arenas as $arena) {
                    $main->new($p, "Title", "§l§bLOBBY");
                    $main->setLine($p, 1, ($this->plugin->getConfig()->get("line-1")));
                    $main->setLine($p, 2, ($this->plugin->getConfig()->get("line-2")));
                    $main->setLine($p, 3, ($this->plugin->getConfig()->get("line-3")));
                    $main->setLine($p, 4, ($this->plugin->getConfig()->get("line-4")));
                    $main->setLine($p, 5, ($this->plugin->getConfig()->get("line-5")));
                    $main->setLine($p, 6, ($this->plugin->getConfig()->get("line-6")));
                    $main->setLine($p, 7, ($this->plugin->getConfig()->get("line-7")));
                    $main->setLine($p, 8, ($this->plugin->getConfig()->get("line-8")));
                    $main->setLine($p, 9, ($this->plugin->getConfig()->get("line-9")));
                    $main->setLine($p, 10, ($this->plugin->getConfig()->get("line-10")));
                    $main->setLine($p, 11, ($this->plugin->getConfig()->get("line-11")));
                    $main->setLine($p, 12, ($this->plugin->getConfig()->get("line-12")));
                    $main->setLine($p, 13, ($this->plugin->getConfig()->get("line-13")));
                    $main->setLine($p, 14, ($this->plugin->getConfig()->get("line-14")));
                    $main->setLine($p, 15, ($this->plugin->getConfig()->get("line-15")));
                    $main->getObjectiveName($p);
			//}
        }
    }
}
