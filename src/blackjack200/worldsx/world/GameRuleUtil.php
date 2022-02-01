<?php

namespace blackjack200\worldsx\world;

use blackjack200\worldsx\world\types\GameRuleParser;
use pocketmine\world\format\io\data\BaseNbtWorldData;

class GameRuleUtil {
	public static function parse(BaseNbtWorldData $data) : ?GameRuleCollection {
		$rules = $data->getCompoundTag()->getCompoundTag("GameRules");
		if ($rules === null) {
			$rules = GameRuleParser::getDefaultTags();
			$data->getCompoundTag()->setTag('GameRules', $rules);
			$data->save();
		}
		return GameRuleCollection::from($rules);
	}

	public static function save(BaseNbtWorldData $data, GameRuleCollection $rules) : void {
		$data->getCompoundTag()->setTag('GameRules', $rules->toCompoundTag());
		$data->save();
	}
}