<?php

namespace brokiem\QuickBoard;

use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class BoardTask extends Task {

    public function __construct(MainBoard $plugin)
    {
        $this->plugin = $plugin;
    }
    
    public function onRun(int $tick)
    {
        $main = $this->plugin;
		$qbline = $this->plugin->getConfig()->get("quickboard-lines");
      
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
                $main->new($p, "Title", ($this->plugin->getConfig()->get("quickboard-title")));
                $c = 0;
                foreach ((array)$qbline as $lines){
                    $c++;
                    if($c <= 15){
                        $main->setLine($p, $c, $lines);
                    }
                }
                $main->getObjectiveName($p);
        }
    }
}
