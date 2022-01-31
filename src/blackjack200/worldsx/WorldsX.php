<?php

namespace blackjack200\worldsx;

use blackjack200\worldsx\command\WorldsXCommand;
use blackjack200\worldsx\generator\VoidGenerator;
use blackjack200\worldsx\lang\Language;
use pocketmine\plugin\PluginBase;
use pocketmine\world\generator\GeneratorManager;
use Webmozart\PathUtil\Path;

class WorldsX extends PluginBase {
	protected function onEnable() : void {
		$this->saveDefaultConfig();
		$language = $this->setupLanguage();
		$this->getServer()->getCommandMap()->register('wx', new WorldsXCommand($language));
		GeneratorManager::getInstance()->addGenerator(VoidGenerator::class, "void", fn() => null);
	}

	protected function onDisable() : void {

	}

	protected function setupLanguage() : Language {
		$languages = [
			'en_US',
			'zh_CN',
		];
		foreach ($languages as $lang) {
			$this->saveResource("lang/$lang.yml", true);
		}
		//$selectedLanguage = $this->getConfig()->get('language', 'en_US');
		$selectedLanguage = 'en_US';
		$filepath = Path::join($this->getDataFolder(), "lang/$selectedLanguage.yml");
		if (!file_exists($filepath)) {
			$this->getLogger()->error("Language file $selectedLanguage.yml not found, using en_US.yml");
			$filepath = Path::join($this->getDataFolder(), "lang/$selectedLanguage.yml");
		}
		return new Language($selectedLanguage, yaml_parse_file($filepath));
	}
}