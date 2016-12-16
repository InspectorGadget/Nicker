<?php

namespace RTG\Nick;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;

class NickName extends PluginBase implements Listener {

/**
* All rights reserved RTGNetworkkk
* InspectorGadget (c)
* GitHub: <https://github.com/RTGNetworkkk>
*/
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("bannednames.txt");
		$this->getLogger()->warning("
* Nicker 1.0.0
* Starting..
		");
		$this->bans = new Config($this->getDataFolder() . "bannednames.txt", Config::ENUM, array());
		$this->bans->getAll();
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $param) {
		switch(strtolower($cmd->getName())) {
		
			case "nicker":
			if($sender->hasPermission("nick.command")) {
				if(isset($param[0])) {
					switch(strtolower($param[0])) {
					
						case "set":
							if(isset($param[1])) {
							
								$n = $param[1];
								$b = $this->bans->getAll();
								
								if($sender instanceof Player) {
									if($this->bans->get($n) === true) {
											$sender->sendMessage("You cant use $n");
									}
									else {
										$sender->setNameTag("** $n");
										$sender->setDisplayName("** $n");
										$sender->sendMessage("You nick has been set to $n");
									}
								}
							}
							else {
								$sender->sendMessage("/nicker set <name>");
							}
							return true;
						break;
						
						case "off":
							$name = $sender->getName();
							
							if($sender instanceof Player) {
							
								$sender->setNameTag($name);
								$sender->setDisplayName($name);
								$sender->sendMessage("You nick has been reset!");
							
							}
							return true;
						break;
					}
				}
				else {
					$sender->sendMessage("/nicker < set | off >");
				}
			}
			else {
				$sender->sendMessage("You have no permission to use this command!");
			}
				return true;
			break;
		}
	}
	
	public function onJoin(PlayerQuitEvent $e) { // To prevent client crashing!
	
		$p = $e->getPlayer();
		$n = $p->getName();
		
		$p->setNameTag($n);
		$p->setDisplayName($n);
	
	}
	
	
	public function onDisable() {
	}
	
}
