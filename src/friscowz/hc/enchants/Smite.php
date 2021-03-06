<?php
namespace friscowz\hc\enchants;

use pocketmine\Player;

use pocketmine\item\enchantment\Enchantment;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use friscowz\hc\Myriad;

class Smite extends VanillaEnchant implements Listener{
	
	CONST MOBS = ["zombie", "skeleton", "wither", "witherskeleton", "witherskeleton", "zombiepigman", "pigzombie"];

    public function __construct(Myriad $plugin){
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }
	
	/*
	 * @void onDamage
	 * @param EntityDamageEvent $event
	 * @priority LOWEST
	 * ignoreCancelled true
	 */
	
	public function onDamage(EntityDamageEvent $event): void{
	    $player = $event->getEntity();
	    $ref = new \ReflectionClass($player);
	    $matched = false;
	    if($event instanceof EntityDamageByEntityEvent){
		   $damager = $event->getDamager();
		   if(!$damager instanceof Player){
			  return;
			}
		  foreach(self::MOBS as $mob){
		    if(stripos($mob, strtolower($ref->getShortName())) !== false){
			   $matched = true;
			 }
		  }
		  if($matched == false){
			return;
		  }
		  $item = $damager->getInventory()->getItemInHand();
		  if($item->hasEnchantment(Enchantment::SMITE)){
			 $level = $this->getEnchantmentLevel($item, Enchantment::SMITE);
		    $base = $event->getDamage();
		    $add = $this->getExtraDamage(Enchantment::SMITE, $base, $level);
		    $event->setDamage($base + $add);
			}
		}
	}
}
