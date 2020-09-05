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
use brokiem\QuickBoard\factions\FactionsInterface;
use brokiem\QuickBoard\factions\PiggyFactions;

use room17\SkyBlock\session\BaseSession as SkyBlockSession;
use room17\SkyBlock\island\RankIds;
use DaPigGuy\PiggyFactions\players\PlayerManager;

class QBListener implements Listener {
    
	private $plugin;
	
   	public function __construct(MainBoard $plugin)
   	{
   		$this->plugin = $plugin;
   	}
	
   	public function getIsleState(Player $player){
		$session = $this->skyBlock->getSessionManager()->getSession($player);
		if((is_null($session)) || (!$session->hasIsland())){
			return "No Island";
		}
		$isle = $session->getIsland();
		return $isle->isLocked() ? "Locked" : "Unlocked";
	}

   	public function getIsleBlocks(Player $player){
		$session = $this->skyBlock->getSessionManager()->getSession($player);
		if((is_null($session)) || (!$session->hasIsland())){
		return "No Island";
   	}
   	$isle = $session->getIsland();
		return $isle->getBlocksBuilt();
  	}


	public function getIsleMembers(Player $player){
		$session = $this->skyBlock->getSessionManager()->getSession($player);
		if((is_null($session)) || (!$session->hasIsland())){
			return "No Island";
		}

		$isle = $session->getIsland();
		return count($isle->getMembers());
	}
	
	public function getIsleSize(Player $player){
		$session = $this->skyBlock->getSessionManager()->getSession($player);

		if((is_null($session)) || (!$session->hasIsland())){
			return "No Island";
		}
		$isle = $session->getIsland();
		return $isle->getCategory();
	}

	public function getIsleRank(Player $player){
		$session = $this->skyBlock->getSessionManager()->getSession($player);
		if((is_null($session)) || (!$session->hasIsland())){
			return "No Island";
		}
		switch($session->getRank()){
			case RankIds::MEMBER:
				return "Member";
			case RankIds::OFFICER:
				return "Officer";
			case RankIds::LEADER:
				return "Leader";
			case RankIds::FOUNDER:
				return "Founder";
		}
			return "No Rank";
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
        $holder = str_replace("%server_online%", count($player->getServer()->getOnlinePlayers()), $holder);
        $holder = str_replace("%max_online%", $player->getServer()->getMaxPlayers(), $holder);
        $holder = str_replace("%server_tps%", $player->getServer()->getTicksPerSecond(), $holder);
        $holder = str_replace("%player_ping%", $player->getPing(), $holder);
        $holder = str_replace("%server_load%", $player->getServer()->getTickUsage(), $holder);
	$holder = str_replace("%server_query%", $total, $holder);
        $holder = str_replace("%item_id%", $player->getInventory()->getItemInHand()->getId(), $holder);
	$holder = str_replace("%item_meta%", $player->getInventory()->getItemInHand()->getDamage(), $holder);
	$holder = str_replace("%player_ip%", $player->getAddress(), $holder);
	$holder = str_replace("%player_x%", $player->getFloorX(), $holder);
	$holder = str_replace("%player_y%", $player->getFloorY(), $holder);
	$holder = str_replace("%player_z%", $player->getFloorZ(), $holder);
 	$holder = str_replace("%player_world%", $level->getFolderName(), $holder);
	$holder = str_replace("%world_player_count%", count($level->getPlayers()), $holder);
	$holder = str_replace("%date%", date("H:i a"), $holder);

	    /** EconomyAPI */
	$economyapi = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	if (!is_null($economyapi)) {
		$holder = str_replace('%player_money%', $economyapi->myMoney($player), $holder);
	} else {
                $holder = str_replace('%player_money%', "EconomyAPI Not Installed!", $holder);
	}
	   /** PurePerms */
	$pureperms = $this->plugin->getServer()->getPluginManager()->getPlugin("PurePerms");
	if (!is_null($pureperms)) {
		$holder = str_replace('%pp_rank%', $pureperms->getUserDataMgr()->getGroup($player)->getName(), $holder);
		$holder = str_replace('%pp_prefix%', $pureperms->getUserDataMgr()->getNode($player, "prefix"), $holder);
		$holder = str_replace('%pp_suffix%', $pureperms->getUserDataMgr()->getNode($player, "suffix"), $holder);
	} else {
                $holder = str_replace('%pp_rank%', "PurePerms Not Installed!", $holder);
		$holder = str_replace('%pp_prefix%', "PurePerms Not Installed!", $holder);
		$holder = str_replace('%pp_suffix%', "PurePerms Not Installed!", $holder);
	}
	   /** FactionsPro */
	$factionspro = $this->plugin->getServer()->getPluginManager()->getPlugin("FactionsPro");
	if (!is_null($factionspro)) {
	$fp = $factionspro->getPlayerFaction($player->getName());
		$holder = str_replace('%fp_faction_name%', $fp, $holder);
		$holder = str_replace('%fp_faction_power%', $factionspro->getFactionPower($fp), $holder);
	} else {
                $holder = str_replace('%fp_faction_name%', "FactionsPro Not Installed!", $holder);
		$holder = str_replace('%fp_faction_power%', "FactionsPro Not Installed!", $holder);
	}
	   /** SkyBlock */
	$skyblock = $this->plugin->getServer()->getPluginManager()->getPlugin("SkyBlock");
	if (!is_null($skyblock)) {
		$holder = str_replace('%is_state%', $this->getIsleState($player), $holder);
		$holder = str_replace('%is_blocks%', $this->getIsleBlocks($player), $holder);
		$holder = str_replace('%is_members%', $this->getIsleMembers($player), $holder);
		$holder = str_replace('%is_size%', $this->getIsleSize($player), $holder);
		$holder = str_replace('%is_rank%', $this->getIsleRank($player), $holder);
	} else {
                $holder = str_replace('%is_state%', "SkyBlock Not Installed!", $holder);
		$holder = str_replace('%is_blocks%', "SkyBlock Not Installed!", $holder);
		$holder = str_replace('%is_members%', "SkyBlock Not Installed!", $holder);
		$holder = str_replace('%is_size%', "SkyBlock Not Installed!", $holder);
		$holder = str_replace('%is_rank%', "SkyBlock Not Installed!", $holder);
	}
	   /** RedSkyBlock */
	$redskyblock = $this->plugin->getServer()->getPluginManager()->getPlugin("RedSkyBlock");
	if (!is_null($redskyblock)) {
		$holder = str_replace('%red_island_name%', $redskyblock->getIslandName($player), $holder);
		$holder = str_replace('%red_island_members%', $redskyblock->getMembers($player), $holder);
		$holder = str_replace('%red_island_banned%', $redskyblock->getBanned($player), $holder);
		$holder = str_replace('%red_island_locked_status%', $redskyblock->getLockedStatus($player), $holder);
		$holder = str_replace('%red_island_size%', $redskyblock->getSize($player), $holder);
		$holder = str_replace('%red_island_rank%', $redskyblock->calcRank(strtolower($player->getName())), $holder);
		$holder = str_replace('%red_island_value%', $redskyblock->getValue($player), $holder);
	} else {
                $holder = str_replace('%red_island_name%', "RedSkyBlock Not Installed!", $holder);
		$holder = str_replace('%red_island_members%', "RedSkyBlock Not Installed!", $holder);
		$holder = str_replace('%red_island_banned%', "RedSkyBlock Not Installed!", $holder);
		$holder = str_replace('%red_island_locked_status%', "RedSkyBlock Not Installed!", $holder);
		$holder = str_replace('%red_island_size%', "RedSkyBlock Not Installed!", $holder);
                $holder = str_replace('%red_island_rank%', "RedSkyBlock Not Installed!", $holder);
                $holder = str_replace('%red_island_value%', "RedSkyBlock Not Installed!", $holder);
	}
	   /** KDR */
	$kdr = $this->plugin->getServer()->getPluginManager()->getPlugin("KDR");
	if (!is_null($kdr)) {
		$holder = str_replace('%kdr%', $kdr->getProvider()->getKillToDeathRatio($player), $holder);
		$holder = str_replace('%kills%', $kdr->getProvider()->getPlayerKillPoints($player), $holder);
		$holder = str_replace('%deaths%', $kdr->getProvider()->getPlayerDeathPoints($player), $holder);
	} else {
                $holder = str_replace('%kdr%', "KDR Not Installed!", $holder);
		$holder = str_replace('%kills%', "KDR Not Installed!", $holder);
		$holder = str_replace('%deaths%', "KDR Not Installed!", $holder);
	}
	   /** CPS */
	$cps = $this->plugin->getServer()->getPluginManager()->getPlugin("CPS");
	if (!is_null($cps)) {
		$holder = str_replace('%cps%', $this->cps->getClicks($player), $holder);
	} else {
                $holder = str_replace('%cps%', "CPS Not Installed!", $holder);
        }
	   /** LevelUP */
	$levelup = $this->plugin->getServer()->getPluginManager()->getPlugin("LevelUP");
	if (!is_null($levelup)) {
		$holder = str_replace('%lu_level%', $levelup->getLevel($player), $holder);
		$holder = str_replace('%lu_exp%', $levelup->getExp($player), $holder);
		$holder = str_replace('%lu_exp_count%', $levelup->getExpCount($player), $holder);
		$holder = str_replace('%lu_kills_count%', $levelup->getKills($player), $holder);
		$holder = str_replace('%lu_deaths_count%', $levelup->getDeaths($player), $holder);
	} else {
                $holder = str_replace('%lu_level%', "LevelUP Not Installed!", $holder);
		$holder = str_replace('%lu_exp%', "LevelUP Not Installed!", $holder);
		$holder = str_replace('%lu_exp_count%', "LevelUP Not Installed!", $holder);
		$holder = str_replace('%lu_kills_count%', "LevelUP Not Installed!", $holder);
		$holder = str_replace('%lu_deaths_count%', "LevelUP Not Installed!", $holder);
	}
	   /** PiggyFactions */
	$pf = $this->plugin->getServer()->getPluginManager()->getPlugin("PiggyFactions");
	$this->factionsAPI = new PiggyFactions();
	if (!is_null($pf)) {
		$holder = str_replace('%pf_faction_name%', $this->factionsAPI->getPlayerFaction($player), $holder);
		$holder = str_replace('%pf_faction_rank%', $this->factionsAPI->getPlayerRank($player), $holder);
	} else {
                $holder = str_replace('%pf_faction_name%', "PiggyFactions Not Installed!", $holder);
		$holder = str_replace('%pf_faction_rank%', "PiggyFactions Not Installed!", $holder);
	}
	return ((string) $holder);
    }

}
