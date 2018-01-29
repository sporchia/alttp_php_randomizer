<?php namespace ALttP\Region\LightWorld;

use ALttP\Item;
use ALttP\Location;
use ALttP\Region;
use ALttP\Support\LocationCollection;
use ALttP\World;

/**
 * North East Light World Region and it's Locations contained within
 */
class NorthEast extends Region {
	protected $name = 'Light World';

	/**
	 * Create a new Light World Region and initalize it's locations
	 *
	 * @param World $world World this Region is part of
	 *
	 * @return void
	 */
	public function __construct(World $world) {
		parent::__construct($world);

		$this->locations = new LocationCollection([
			new Location\Npc("Link's Uncle", 0x2DF45, null, $this),
			new Location\Chest("Secret Passage", 0xE971, null, $this),
			new Location\Chest("Sahasrahla's Hut - Left", 0xEA82, null, $this),
			new Location\Chest("Sahasrahla's Hut - Middle", 0xEA85, null, $this),
			new Location\Chest("Sahasrahla's Hut - Right", 0xEA88, null, $this),
			new Location\Npc("Sahasrahla", 0x2F1FC, null, $this),
			new Location\Npc\Zora("King Zora", 0xEE1C3, null, $this),
			new Location\Npc\Witch("Potion Shop", 0x180014, null, $this),
			new Location\Standing("Zora's Ledge", 0x180149, null, $this),
			new Location\Chest("Waterfall Fairy - Left", 0xE9B0, null, $this),
			new Location\Chest("Waterfall Fairy - Right", 0xE9D1, null, $this),
		]);
	}

	/**
	 * Set Locations to have Items like the vanilla game.
	 *
	 * @return $this
	 */
	public function setVanilla() {
		$this->locations["Link's Uncle"]->setItem(Item::get('L1SwordAndShield'));
		$this->locations["Secret Passage"]->setItem(Item::get('Lamp'));
		$this->locations["Sahasrahla's Hut - Left"]->setItem(Item::get('FiftyRupees'));
		$this->locations["Sahasrahla's Hut - Middle"]->setItem(Item::get('ThreeBombs'));
		$this->locations["Sahasrahla's Hut - Right"]->setItem(Item::get('FiftyRupees'));
		$this->locations["Sahasrahla"]->setItem(Item::get('PegasusBoots'));
		$this->locations["King Zora"]->setItem(Item::get('Flippers'));
		$this->locations["Potion Shop"]->setItem(Item::get('Powder'));
		$this->locations["Zora's Ledge"]->setItem(Item::get('PieceOfHeart'));
		$this->locations["Waterfall Fairy - Left"]->setItem(Item::get('RedShield'));
		$this->locations["Waterfall Fairy - Right"]->setItem(Item::get('RedBoomerang'));

		return $this;
	}


	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for No Major Glitches
	 *
	 * @return $this
	 */
	public function initNoMajorGlitches() {
		$this->locations["Sahasrahla"]->setRequirements(function($locations, $items) {
			return $items->has('PendantOfCourage');
		});

		$this->locations["King Zora"]->setRequirements(function($locations, $items) {
			return $items->canLiftRocks() || $items->has('Flippers');
		});

		$this->locations["Potion Shop"]->setRequirements(function($locations, $items) {
			return $items->has('Mushroom');
		});

		$this->locations["Zora's Ledge"]->setRequirements(function($locations, $items) {
			return $items->has('Flippers');
		});

		$this->locations["Waterfall Fairy - Left"]->setRequirements(function($locations, $items) {
			return $items->has('Flippers');
		});

		$this->locations["Waterfall Fairy - Right"]->setRequirements(function($locations, $items) {
			return $items->has('Flippers');
		});

		return $this;
	}

	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Overworld Glitches Mode
	 *
	 * @return $this
	 */
	public function initOverworldGlitches() {
		$this->initNoMajorGlitches();

		$this->locations["King Zora"]->setRequirements(function($locations, $items) {
			return true;
		});

		$this->locations["Zora's Ledge"]->setRequirements(function($locations, $items) {
			return $items->has('Flippers')
				|| ($items->has('PegasusBoots') && $items->has('MoonPearl'));
		});

		$this->locations["Waterfall Fairy - Left"]->setRequirements(function($locations, $items) {
			return $items->has('Flippers') || $items->has('MoonPearl');
		});

		$this->locations["Waterfall Fairy - Right"]->setRequirements(function($locations, $items) {
			return $items->has('Flippers') || $items->has('MoonPearl');
		});

		return $this;
	}
}
