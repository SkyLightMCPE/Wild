<?php
declare(strict_types=1);
namespace SkyLightMCPE;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\entity\EntityDamageEvent;

class Main extends PluginBase{
    
    public $iswildin = [];
    
    public function onEnable(){
              $this->getLogger()->info(C::GREEN . "Wild enabled!");
    }
    
    public function onDisable(){
              $this->getLogger()->info(C::RED . "Wild disabled!");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
        if(strtolower($cmd->getName()) !== "wild") return false;
        if(!$sender instanceof Player){
            $sender->sendMessage("Use this command in game.");
            return false;
        }
        if(!$sender->hasPermission("wild.command")){
            $sender->sendMessage(C::RED . "You don't have permission to use this command.");
            return false;
        }
           array_push($this->iswildin, $sender->getName());
           $sender->teleport(new Vector3(rand(1, 999), 128, rand(1, 999)));
           $sender->sendMessage(C::RED . "Teleporting...");
           $sender->sendMessage(C::BLUE . "If you have been teleported to air you wont take any fall damage!");
           return true;
     }
    public function onDamage(EntityDamageEvent $e){
        $p = $e->getEntity();
        if(!$p instanceof Player) return;
        if(($key = array_search($p->getName(), $this->iswildin)) !== false){
            unset($this->iswildin[$key]);
            $e->setCancelled();
        }
    }
}
