<?php

namespace blackjack200\worldsx\world;

use blackjack200\worldsx\world\types\GameRuleParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\GameRule;

class GameRuleCollection {
	protected array $rules = [];

	public function __construct(array $rules) {
		$this->rules = $rules;
	}

	public static function from(CompoundTag $tag) : self {
		$rules = [];
		foreach ($tag->getValue() as $internal => $value) {
			$rules[$internal] = GameRuleParser::convertVal($internal, json_decode((string)$value->getValue()));
		}
		return new self($rules);
	}

	public function toCompoundTag() : CompoundTag {
		$tag = new CompoundTag();
		foreach ($this->rules as $internal => $value) {
			$t = GameRuleParser::create($internal, $value);
			if ($t !== null) {
				$tag->setString($internal, json_encode($value));
			}
		}
		return $tag;
	}

	/** @return GameRule[] */
	public function toGameRules() : array {
		$rules = [];
		foreach ($this->rules as $internal => $value) {
			$t = GameRuleParser::create($internal, $value);
			if ($t !== null) {
				$rules[$internal] = $t;
			}
		}
		return $rules;
	}

	public function set(string $internal, $val) : void {
		$this->rules[$internal] = GameRuleParser::convertVal($internal, $val);
	}

	/**
	 * @return int|float|bool|null
	 */
	public function get(string $internal) {
		return $this->rules[$internal] ?? GameRuleParser::default($internal);
	}
}