<?php

namespace brokiem\QuickBoard;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

class MainBoard extends PluginBase 
{
    public function onEnable()
    {
        $this->getScheduler()->scheduleRepeatingTask(new Board($this), (int) $this->getConfig()->get("refresh") * 20);
    }
}
