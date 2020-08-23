<?php

namespace brokiem\QuickBoard;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class MainBoard extends PluginBase 
{

    public function onEnable()
    {
        $this->getScheduler()->scheduleRepeatingTask(new Board($this), 40); //2 seconds
    }
}
