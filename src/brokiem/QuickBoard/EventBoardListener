<?php

namespace brokiem\QuickBoard;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use Scoreboards\Scoreboards;

class BoardEventListener implements Listener
{

    public function onQuit(PlayerQuitEvent $event){
		    $player = $event->getPlayer();
		    $api = Scoreboards::getInstance();
                $api->remove($player);
	  }
  
}
