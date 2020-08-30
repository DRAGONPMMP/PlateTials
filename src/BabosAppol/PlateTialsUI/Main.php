<?php

namespace BabosAppol\PlateTialsUI;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{

    public function onEnable()
    {

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() == "heal") {
            if ($sender instanceof Player) {
                if ($sender->hasPermission("pt.heal")) {
                    $this->heal($sender);
                } else {
                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cYou dont have permission to use this command!");
                }
            } else {
                if (!isset($args[0])) {
                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cPlease use: heal [playername]");
                } else {
                    $p = $this->getServer()->getPlayer($args[0]);
                    if ($p instanceof Player) {
                        $p->setHealth(20);
                        $p->sendMessage("§7[§aPlate§6Tials§7] §aYour health has been filled to 20 by CONSOLE");
                        $sender->sendMessage("§7[§aPlate§6Tials§7] §aYou've been healed " . $p->getName());
                    } else {
                        $sender->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player named: " . $args[0]);
                    }
                }
            }
        }
        if ($command->getName() == "feed") {
            if ($sender instanceof Player) {
                if ($sender->hasPermission("pt.feed")) {
                    $this->feed($sender);
                } else {
                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cYou dont have permission to use this command!");
                }
            } else {
                if (!isset($args[0])) {
                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cPlease use: feed [playername]");
                } else {
                    $p = $this->getServer()->getPlayer($args[0]);
                    if ($p instanceof Player) {
                        $p->setFood(20);
                        $p->sendMessage("§7[§aPlate§6Tials§7] §aYour food has been filled to 20 by CONSOLE");
                        $sender->sendMessage("§7[§aPlate§6Tials§7] §aYou've been feeded " . $p->getName());
                    } else {
                        $sender->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player named: " . $args[0]);
                    }
                }
            }
        }
        if ($command->getName() == "clear") {
            if ($sender instanceof Player) {
                if ($sender->hasPermission("pt.clear")) {
                    $this->clear($sender);
                }
            } else {
                if (!isset($args[0])) {
                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cPlease use: clear [inventory | armour | effect] [playername]");
                } else {
                    switch (strtolower($args[0])) {
                        case "inventory":
                            if (isset($args[1])) {
                                $p = $this->getServer()->getPlayer($args[1]);
                                if ($p instanceof Player) {
                                    $p->getInventory()->clearAll();
                                    $sender->sendMessage("§7[§aPlate§6Tials§7] §aYou've cleared " . $p->getName() . " Inventory!");
                                } else {
                                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player named: " . $args[1]);
                                }
                            } else {
                                $sender->sendMessage("§7[§aPlate§6Tials§7] §cPlease use: clear [inventory | armour | effect] [playername]");
                            }
                            break;

                        case "armour":
                            if (isset($args[1])) {
                                $p = $this->getServer()->getPlayer($args[1]);
                                if ($p instanceof Player) {
                                    $p->getArmorInventory()->clearAll();
                                    $sender->sendMessage("§7[§aPlate§6Tials§7] §aYou've cleared " . $p->getName() . " Armour!");
                                } else {
                                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player named: " . $args[1]);
                                }
                            } else {
                                $sender->sendMessage("§7[§aPlate§6Tials§7] §cPlease use: clear [inventory | armour | effect] [playername]");
                            }
                            break;

                        case "effect":
                            if (isset($args[1])) {
                                $p = $this->getServer()->getPlayer($args[1]);
                                if ($p instanceof Player) {
                                    $p->removeAllEffects();
                                    $sender->sendMessage("§7[§aPlate§6Tials§7] §aYou've cleared " . $p->getName() . " Effectsclea!");
                                } else {
                                    $sender->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player named: " . $args[1]);
                                }
                            } else {
                                $sender->sendMessage("§7[§aPlate§6Tials§7] §cPlease use: clear [inventory | armour | effect] [playername]");
                            }
                            break;
                    }
                }
            }
        }
        return true;
    }

    public function heal($player)
    {
        $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if (!$data[0] == true) {
                $player->setHealth(20);
                $player->sendMessage("§7[§aPlate§6Tials§7] §aYour health has been filled to 20 by Yourself!");
            } else {
                if ($data[1] === null) {
                    $player->sendMessage("§7[§aPlate§6Tials§7] §cPlease type a player name!");
                } else {
                    $p = $this->getServer()->getPlayer($data[1]);
                    if ($p instanceof Player) {
                        $p->setHealth(20);
                        $p->sendMessage("§7[§aPlate§6Tials§7] §aYour health has been filled to 20 by " . $player->getName());
                        $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been healed " . $p->getName());
                    } else {
                        $player->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player named: " . $data[1]);
                    }
                }
            }
        });
        $form->setTitle("§aPlate§6Tials");
        $form->addToggle("§cHeal me §9/ §cHeal Other Player", false);
        $form->addInput("Type the player name!", "Leave it blank if you want to heal yourself!");
        $form->sendToPlayer($player);
        return $form;
    }

    public function feed($player){
        $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                return true;
            }
            if (!$data[0] == true) {
                $player->setFood(20);
                $player->sendMessage("§7[§aPlate§6Tials§7] §aYour food has been filled to 20 by Yourself!");
            } else {
                if ($data[1] === null) {
                    $player->sendMessage("§7[§aPlate§6Tials§7] §cPlease type a player name!");
                } else {
                    $p = $this->getServer()->getPlayer($data[1]);
                    if ($p instanceof Player) {
                        $p->setFood(20);
                        $p->sendMessage("§7[§aPlate§6Tials§7] §aYour food has been filled to 20 by " . $player->getName());
                        $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been feeded " . $p->getName());
                    } else {
                        $player->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player named: " . $data[1]);
                    }
                }
            }
        });
        $form->setTitle("§aPlate§6Tials");
        $form->addToggle("§cFeed me §9/ §cFeed Other Player", false);
        $form->addInput("Type the player name!", "Leave it blank if you want to feed yourself!");
        $form->sendToPlayer($player);
        return $form;
    }

    public function clear($player){
        $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function (Player $player, array $data = null){
           if($data === null){
               return true;
           }
           if(!$data[3] == null){
               $p = $this->getServer()->getPlayer($data[3]);
               if($p instanceof Player){
                if($data[0] == true){
                    $p->getInventory()->clearAll();
                    $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been cleared " . $p->getName() . " Inventory!");
                }
                if($data[1] == true){
                    $p->getArmorInventory()->clearAll();
                    $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been cleared " . $p->getName() . " Armour!");
                }
                if($data[2] == true){
                    $p->removeAllEffects();
                    $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been cleared " . $p->getName() . " Effects!");
                }
               } else {
                   $player->sendMessage("§7[§aPlate§6Tials§7] §cThere's no player with named: " . $data[3]);
               }
           } else {
               if($data[0] == true){
                   $player->getInventory()->clearAll();
                   $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been cleared your Inventory!");
               }
               if($data[1] == true){
                   $player->getArmorInventory()->clearAll();
                   $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been cleared your Armour!");
               }
               if($data[2] == true){
                   $player->removeAllEffects();
                   $player->sendMessage("§7[§aPlate§6Tials§7] §aYou've been cleared your Effects!");
               }
           }
        });
        $form->setTitle("§aPlate§6Tials");
        $form->addToggle("§aClear Inventory");
        $form->addToggle("§bClear Armour");
        $form->addToggle("§cClear Effects");
        $form->addInput("Type the player name!", "Leave it blank if you want to clear yourself!");
        $form->sendToPlayer($player);
        return $form;
    }

}                                                        