<?php

namespace H3317\ToggleInventory;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\item\Item;

class ToggleInventory extends PluginBase{

	public function onEnable(){//プラグイン読み込み時の挙動
		$this->loadYml();//オプションファイルの読み込み
	}

	public function onCommand(CommandSender $sender, Command $cmd, $label, array $sub){//サーバー読み込み時の挙動
		$n = $sender->getName();
		$m = "[ToggleInventory] ";
		if($n == "CONSOLE"){
			$sender->sendMessage("§4このコマンドはゲーム内で実行して下さい。");
			return true;
		}
		$getInv = [];
		$inv = $sender->getInventory();
		if(!isset($this->si[$n])) $this->si[$n] = [];
		$getInv = [];
		foreach($inv->getContents() as $gI){
			if($gI->getID() !== 0 and $gI->getCount() > 0) $getInv[] = [$gI->getID(),$gI->getDamage(),$gI->getCount() ];
		}
		$setInv = [];
		foreach($this->si[$n] as $sI)
			$setInv[] = Item::get($sI[0], $sI[1], $sI[2]);
		$this->si[$n] = $getInv;
		$inv->setContents($setInv);
		$this->saveYml();
		$sender->sendMessage("§2 インベントリを切り替えました");
		return true;
	}

	public function loadYml(){
		@mkdir($this->getServer()->getDataPath() . "/plugins/ToggleInventory/");
		$this->subInventory = new Config($this->getServer()->getDataPath() . "/plugins/ToggleInventory/" . "ToggleInventory.yml", Config::YAML);
		$this->si = $this->subInventory->getAll();
	}

	public function saveYml(){
		asort($this->si);
		$this->subInventory->setAll($this->si);
		$this->subInventory->save();
		$this->loadYml();
	}
	}