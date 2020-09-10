<?php

namespace brokiem\QuickBoard\factions;

use pocketmine\Player;

interface FactionsInterface
{
    
    public function getAPI();

    public function getPlayerFaction(Player $player);

    public function getPlayerRank(Player $player);
}
