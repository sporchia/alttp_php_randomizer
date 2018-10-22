<?php namespace ALttP\Http\Controllers;

use ALttP\Item;
use ALttP\Location;
use ALttP\Randomizer;
use ALttP\Rom;
use ALttP\Sprite;
use ALttP\Support\ItemCollection;
use ALttP\World;
use Cache;
use Illuminate\Http\Request;

class SettingsController extends Controller {
	protected $items = [];
	protected $drops = [];

	public function __construct() {
		$this->drops = config('item.drop');
		$this->items = array_merge(...array_values(array_only(config('item'), ['advancement', 'nice', 'junk', 'dungeon'])));
		$this->items['KeyD3']++;
	}

	public function item(Request $request) {
		return config('alttp.randomizer.item');
	}

	public function entrance(Request $request) {
		return config('alttp.randomizer.entrance');
	}

	public function customizer(Request $request) {
		return Cache::rememberForever('customizer_settings', function() {
			$world = World::factory();
			$items = Item::all();
			$sprites = Sprite::all();
			return [
				'locations' => array_values($world->getLocations()->filter(function($location) {
					return !$location instanceof Location\Prize\Event
						&& !$location instanceof Location\Trade;
				})->map(function($location) {
					return [
						'hash' => base64_encode($location->getName()),
						'name' => $location->getName(),
						'region' => $location->getRegion()->getName(),
						'class' => $location instanceof Location\Fountain ? 'bottles'
							: ($location instanceof Location\Medallion ? 'medallions'
							: ($location instanceof Location\Prize ? 'prizes' : 'items')),
					];
				})),
				'prizepacks' => array_values(array_map(function($pack) {
					return [
						'name' => $pack->getName(),
						'slots' => count($pack->getDrops()),
					];
				}, $world->getPrizePacks())),
				'items' => array_merge([
						['value' => 'auto_fill', 'name' => 'Random', 'placed' => 0],
						['value' => 'BottleWithRandom', 'name' => 'Bottle (Random)', 'count' => 4, 'placed' => 0],
					],
					$items->filter(function($item) {
					return !$item instanceof Item\Pendant
						&& !$item instanceof Item\Crystal
						&& !$item instanceof Item\Event
						&& !$item instanceof Item\Programmable
						&& !$item instanceof Item\BottleContents
						&& !in_array($item->getName(), [
							'BigKey',
							'Compass',
							'Key',
							'KeyGK',
							'L2Sword',
							'Map',
							'MapLW',
							'MapDW',
							'multiRNG',
							'PowerStar',
							'singleRNG',
							'TwentyRupees2',
							'HeartContainerNoAnimation',
							'UncleSword',
							'ShopKey',
							'ShopArrow',
						])
						|| $item == Item::get('Triforce');
					})->map(function($item) {
						return [
							'value' => $item->getName(),
							'name' => $item->getNiceName(),
							'count' => $this->items[$item->getName()] ?? 0,
							'placed' => 0,
						];
					})
				),
				'prizes' => array_merge([
						['value' => 'auto_fill', 'name' => 'Random', 'placed' => 0],
					],
					$items->filter(function($item) {
						return $item instanceof Item\Pendant
							|| $item instanceof Item\Crystal;
					})->map(function($item) {
						return [
							'value' => $item->getName(),
							'name' => $item->getNiceName(),
							'count' => 0,
							'placed' => 0,
						];
					})
				),
				'medallions' => array_merge([
						['value' => 'auto_fill', 'name' => 'Random', 'placed' => 0],
					], $items->filter(function($item) {
						return $item instanceof Item\Medallion;
					})->map(function($item) {
						return [
							'value' => $item->getName(),
							'name' => $item->getNiceName(),
							'count' => 0,
							'placed' => 0,
						];
					})
				),
				'bottles' => array_merge([
						['value' => 'auto_fill', 'name' => 'Random', 'placed' => 0],
					], $items->filter(function($item) {
						return $item instanceof Item\Bottle;
					})->map(function($item) {
						return [
							'value' => $item->getName(),
							'name' => $item->getNiceName(),
							'count' => 0,
							'placed' => 0,
						];
					})
				),
				'droppables' => array_merge([
						['value' => 'auto_fill', 'name' => 'Random', 'placed' => 0],
					], array_values($sprites->filter(function($sprite) {
						return $sprite instanceof Sprite\Droppable;
					})->map(function($item) {
						return [
							'value' => $item->getName(),
							'name' => $item->getNiceName(),
							'count' => $this->drops[$item->getName()] ?? 0,
							'placed' => 0,
						];
					})
				)),
			];
		});
	}

	public function rom(Request $request) {
		return [
			'rom_hash' => Rom::HASH,
			'base_file' => mix('js/base2current.json')->toHtml(),
		];
	}

	public function sprites(Request $request) {
		$sprites =  [];
		foreach (config('sprites') as $file => $info) {     //imports this from sprites.php
			$sprites[] = array_merge($info,[				//throw the file name onto everything we know
				'file' => 'resources/sprites/' . $file,
			]);
		}
		return $sprites;
	}
}
