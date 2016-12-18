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
		$this->saveResource("bannednames.yml");
		$this->getLogger()->warning("
* Nicker 1.0.1
* Starting..
		");
		$this->bans = new Config($this->getDataFolder() . "bannednames.yml");
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
								$nn = count($n);
								
								if($sender instanceof Player) {
										if($this->bans->get($n)) {
												$sender->sendMessage("You cant use username called ยงc$n");
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
						
						case "reload":
							if($sender->hasPermission("nick.command.admin")) {
								foreach($this->getServer()->getOnlinePlayers() as $p) {
									$p->setDisplayName($p->getName());
									$p->setNameTag($p->getName());
									$p->sendMessage("Your nick has been reset by an Admin!");
									$this->getLogger()->warning("All nick's has been reset!");
								}
							}
							else {
								$sender->getMessage("You have no permission to use this command.");
							}
							return true;
						break;
						
						case "add":
							if($sender->hasPermission("nick.command.admin")) {
								if(isset($param[0])) {
									$n = $param[0];
									
									$this->bans->set($n, true);
									$sender->sendMessage("You have added $n");
								}
								else {
									$sender->sendMessage("Usage: /nicker add <name>");
								}
							}
							else {
								$sender->sendMessage("You have no permission to use this command.");
							}
							return true;
						break;
						
						case "remove":
							if($sender->hasPermission("nick.command.admin")) {
								if(isset($param[0])) {
								
									$w = $param[0];
									
									if($this->bans->get($w)) {
										$this->bans->remove($w);
									}
									else {
										$sender->sendMessage("$w doesn't exist on the system!");
									}
								}
								else {
									$sender->sendMessage("Usage: /nicker remove <name>");
								}
							}
							else {
								$sender->sendMessage("You have no permission to use this command.");
							}
							return true;
						break;
					}
				}
				else {
					$sender->sendMessage("/nicker < set | off | reload | add | remove >");
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
