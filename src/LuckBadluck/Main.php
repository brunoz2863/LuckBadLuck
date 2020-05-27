<?php

#=========================================================================================================================#

namespace LuckBadluck;

#=========================================================================================================================#

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\utils\Config;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\math\Vector3;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

#=========================================================================================================================#

use onebone\economyapi\EconomyAPI;

#=========================================================================================================================#

class Main extends PluginBase implements Listener{

#=========================================================================================================================#

    public $set = 0;

#=========================================================================================================================#

    public function onEnable(){
     if(!is_dir($this->getDataFolder())) @mkdir($this->getDataFolder());
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $plugin = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
      if(is_null($plugin)) {
       $this->getLogger()->info("Please Install EconomyAPI Plugin");
       $this->getServer()->shutdown();
      }
      $this->getLogger()->info("Fixed plugin, changed by bruno <The code is not mine>");
     }

#=========================================================================================================================#

    public function onRun(int $tick) : void{        
           $touch->processCoolDown();
    }

#=========================================================================================================================#
    
    public function onJoin(PlayerJoinEvent $e){
     $p = $e->getPlayer();
     if($p instanceof Player){
      $data = new Config($this->getDataFolder().'data.yml', Config::YAML);
      $x = $data->get("well.x");
      $y = $data->get("well.y");
      $z = $data->get("well.z");
      $text = "§bLuckyBadLuck\n§l§eClick me";
      $p->getLevel()->addParticle(new FloatingTextParticle(new Vector3($x + 0.5, $y + 2.5, $z + 0.5), '', $text), array($p));
     }
    }

#=========================================================================================================================#

    public function onTouch(PlayerInteractEvent $e){
     $p = $e->getPlayer();
     $b = $e->getBlock();
     if($p instanceof Player){
      $data = new Config($this->getDataFolder().'data.yml', Config::YAML);
      $x = $data->get("well.x");
      $y = $data->get("well.y");
      $z = $data->get("well.z");
      if($b->x == $x && $b->y == $y + 2 && $b->z == $z || $b->x == $x && $b->y == $y + 1 && $b->z == $z){
       $this->soulWell($p);
      }
     }
    }

#=========================================================================================================================#

    public function onBlock(BlockBreakEvent $e){
     $p = $e->getPlayer();
     $b = $e->getBlock();
     if($this->set == 1){
      $x = $b->getX();
      $y = $b->getY();
      $z = $b->getZ();
      $data = new Config($this->getDataFolder().'data.yml', Config::YAML);
      if(empty($data->get("well.x")) && empty($data->get("well.y")) && empty($data->get("well.z"))){
       $data->set("well.x", $x);
       $data->set("well.y", $y);
       $data->set("well.z", $z);
       $data->save();
       $text = "§bLuckBadluck\n§l§eClick me";
       $b->getLevel()->addParticle(new FloatingTextParticle(new Vector3($x + 0.5, $y + 2.5, $z + 0.5), '', $text));
       $b->getLevel()->setBlockIdAt($x, $y + 2, $z, 140);
       $b->getLevel()->setBlockIdAt($x, $y + 1, $z, 120);
       $p->sendMessage("§e§l§oLuckBadluck§r§f: §6§oSoulWell Successfully Added§r§f.");
       $this->set = 0;
      }else{
       $p->sendMessage("§e§l§oLuckBadluck§r§f: §6§oSoul Well Has Been Made Please Delete First§r§f.");
       $this->set = 0;
      }
      return;
     }
     if($p->isOP()){
      $data = new Config($this->getDataFolder().'data.yml', Config::YAML);
      $x = $data->get("well.x");
      $y = $data->get("well.y");
      $z = $data->get("well.z");
      if($b->x == $x && $b->y == $y + 2 && $b->z == $z || $b->x == $x && $b->y == $y + 1  && $b->z == $z){
       $data->remove("well.x");
       $data->remove("well.y");
       $data->remove("well.z");
       $data->save();
       $p->sendMessage("§e§l§oLuckBadluck§r§f: §6§oLuckBadluck Successfully Remove§r§f.");
      }
     }
    }

#=========================================================================================================================#

    public function onCommand(CommandSender $p, Command $command, string $label, array $args) : bool{
     if($p instanceof Player){
      if($p->isOP()){
       if($command->getName() === 'luckbadluck'){
        $this->set = 1;
        $p->sendMessage("§e§l§oLuckBadluck§r§f: §6§oPlease Destroy 1 Block§r§f.");
       }
      }else{
       $p->sendMessage("§e§l§oLuckBadluck§r§f: §6§oNup§r§f.");
      }
     }else{
      $p->sendMessage("§e§l§oLuckBadluck§r§f: §6§oPlease Use This Command In The Game§r§f.");
     }
     return true;
    }

#=========================================================================================================================#

    public function soulWell(Player $p){
     $money = EconomyAPI::getInstance()->myMoney($p);
     if($money >= 100){
      EconomyAPI::getInstance()->reduceMoney($p, 10000, true);
      switch(mt_rand(1,20)){
       case 1: EconomyAPI::getInstance()->addMoney($p, 10, true);	    
        $title = "§e§lYou Win";
		$subtitle = "§e§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 2:
        EconomyAPI::getInstance()->addMoney($p, 50, true);		
		$title = "§e§lYou Win";
		$subtitle = "§e§l50$";
        $p->addTitle($title, $subtitle);
       break;
       case 3:
        EconomyAPI::getInstance()->reduceMoney($p, 10, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 4:
        EconomyAPI::getInstance()->addMoney($p, 500, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l500$";
        $p->addTitle($title, $subtitle);
       break;
       case 5:
        EconomyAPI::getInstance()->addMoney($p, 100, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 6:
        EconomyAPI::getInstance()->reduceMoney($p, 100, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 7:
        EconomyAPI::getInstance()->reduceMoney($p, 100, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 8:
        EconomyAPI::getInstance()->addMoney($p, 100, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 9:
        EconomyAPI::getInstance()->addMoney($p, 10, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 10:
        EconomyAPI::getInstance()->addMoney($p, 10, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 11:
        EconomyAPI::getInstance()->addMoney($p, 100, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 12:
        EconomyAPI::getInstance()->addMoney($p, 10, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 13:
        EconomyAPI::getInstance()->reduceMoney($p, 100, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 14:
        EconomyAPI::getInstance()->reduceMoney($p, 100, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 15:
        EconomyAPI::getInstance()->reduceMoney($p, 100, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 16:
        EconomyAPI::getInstance()->addMoney($p, 10, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 17:
        EconomyAPI::getInstance()->reduceMoney($p, 10, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 18:
        EconomyAPI::getInstance()->reduceMoney($p, 10, true);
        $title = "§4§lYou Lose";
		$subtitle = "§4§l10$";
        $p->addTitle($title, $subtitle);
       break;
       case 19:
        EconomyAPI::getInstance()->addMoney($p, 100, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l100$";
        $p->addTitle($title, $subtitle);
       break;
       case 20:
        EconomyAPI::getInstance()->addMoney($p, 10, true);
        $title = "§e§lYou Win";
		$subtitle = "§e§l10$";
        $p->addTitle($title, $subtitle);
       break;
      }
     }else{
      $p->sendMessage("§e§l§oSorry§r§f: §6§oYou don't keep enough money§r§f.");
     }
    }

#=========================================================================================================================#

}

#=========================================================================================================================#