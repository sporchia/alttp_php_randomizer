<?php

namespace Inverted;

use ALttP\Item;
use ALttP\World;
use TestCase;

/**
 * @group Inverted
 */
class DesertPalaceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->world = World::factory('inverted', ['difficulty' => 'test_rules', 'logic' => 'NoGlitches']);
        $this->addCollected(['RescueZelda']);
        $this->collected->setChecksForWorld($this->world->id);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->world);
    }

    /**
     * @param bool $access
     * @param array $items
     * @param array $except
     *
     * @dataProvider entryPool
     */
    public function testEntry(bool $access, array $items, array $except = [])
    {
        if (count($except)) {
            $this->collected = $this->allItemsExcept($except);
        }

        $this->addCollected($items);

        $this->assertEquals($access, $this->world->getRegion('Desert Palace')
            ->canEnter($this->world->getLocations(), $this->collected));
    }

    public function entryPool()
    {
        return [
            [false, []],
            [false, [], ['BookOfMudora']],
            [false, [], ['MoonPearl']],
            [true, ['BookOfMudora', 'MoonPearl', 'DefeatAgahnim']],
            [true, ['BookOfMudora', 'MoonPearl', 'ProgressiveGlove', 'Hammer']],
            [true, ['BookOfMudora', 'MoonPearl', 'PowerGlove', 'Hammer']],
            [true, ['BookOfMudora', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove']],
            [true, ['BookOfMudora', 'MoonPearl', 'TitansMitt']],
        ];
    }

    /**
     * @param string $location
     * @param bool $access
     * @param string $item
     * @param array $items
     * @param array $except
     *
     * @dataProvider fillPool
     */
    public function testFillLocation(string $location, bool $access, string $item, array $items = [], array $except = [])
    {
        if (count($except)) {
            $this->collected = $this->allItemsExcept($except);
        }

        $this->addCollected($items);

        $this->assertEquals($access, $this->world->getLocation($location)
            ->fill(Item::get($item, $this->world), $this->collected));
    }

    public function fillPool()
    {
        return [
            ["Desert Palace - Big Key Chest", false, 'KeyP2', [], ['KeyP2']],
            ["Desert Palace - Compass Chest", false, 'KeyP2', [], ['KeyP2']],

            ["Desert Palace - Big Chest", false, 'BigKeyP2', [], ['BigKeyP2']],

            ["Desert Palace - Boss", false, 'BigKeyP2', [], ['BigKeyP2']],
            ["Desert Palace - Boss", false, 'KeyP2', [], ['KeyP2']],
        ];
    }

    /**
     * @param string $location
     * @param bool $access
     * @param array $items
     * @param array $except
     *
     * @dataProvider accessPool
     */
    public function testLocation(string $location, bool $access, array $items, array $except = [])
    {
        if (count($except)) {
            $this->collected = $this->allItemsExcept($except);
        }

        $this->addCollected($items);

        $this->assertEquals($access, $this->world->getLocation($location)
            ->canAccess($this->collected));
    }

    public function accessPool()
    {
        return [
            ["Desert Palace - Map Chest", false, []],
            ["Desert Palace - Map Chest", false, [], ['BookOfMudora']],
            ["Desert Palace - Map Chest", false, [], ['MoonPearl']],
            ["Desert Palace - Map Chest", true, ['BookOfMudora', 'MoonPearl', 'MoonPearl', 'DefeatAgahnim']],
            ["Desert Palace - Map Chest", true, ['BookOfMudora', 'MoonPearl', 'MoonPearl', 'ProgressiveGlove', 'Hammer']],
            ["Desert Palace - Map Chest", true, ['BookOfMudora', 'MoonPearl', 'MoonPearl', 'PowerGlove', 'Hammer']],
            ["Desert Palace - Map Chest", true, ['BookOfMudora', 'MoonPearl', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove']],
            ["Desert Palace - Map Chest", true, ['BookOfMudora', 'MoonPearl', 'MoonPearl', 'TitansMitt']],

            ["Desert Palace - Big Chest", false, []],
            ["Desert Palace - Big Chest", false, [], ['BookOfMudora']],
            ["Desert Palace - Big Chest", false, [], ['BigKeyP2']],
            ["Desert Palace - Big Chest", false, [], ['MoonPearl']],
            ["Desert Palace - Big Chest", true, ['BookOfMudora', 'BigKeyP2', 'MoonPearl', 'DefeatAgahnim']],
            ["Desert Palace - Big Chest", true, ['BookOfMudora', 'BigKeyP2', 'MoonPearl', 'ProgressiveGlove', 'Hammer']],
            ["Desert Palace - Big Chest", true, ['BookOfMudora', 'BigKeyP2', 'MoonPearl', 'PowerGlove', 'Hammer']],
            ["Desert Palace - Big Chest", true, ['BookOfMudora', 'BigKeyP2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove']],
            ["Desert Palace - Big Chest", true, ['BookOfMudora', 'BigKeyP2', 'MoonPearl', 'TitansMitt']],

            ["Desert Palace - Torch", false, []],
            ["Desert Palace - Torch", false, [], ['BookOfMudora']],
            ["Desert Palace - Torch", false, [], ['MoonPearl']],
            ["Desert Palace - Torch", false, [], ['PegasusBoots']],
            ["Desert Palace - Torch", true, ['BookOfMudora', 'PegasusBoots', 'MoonPearl', 'DefeatAgahnim']],
            ["Desert Palace - Torch", true, ['BookOfMudora', 'PegasusBoots', 'MoonPearl', 'ProgressiveGlove', 'Hammer']],
            ["Desert Palace - Torch", true, ['BookOfMudora', 'PegasusBoots', 'MoonPearl', 'PowerGlove', 'Hammer']],
            ["Desert Palace - Torch", true, ['BookOfMudora', 'PegasusBoots', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove']],
            ["Desert Palace - Torch", true, ['BookOfMudora', 'PegasusBoots', 'MoonPearl', 'TitansMitt']],

            ["Desert Palace - Compass Chest", false, []],
            ["Desert Palace - Compass Chest", false, [], ['BookOfMudora']],
            ["Desert Palace - Compass Chest", false, [], ['MoonPearl']],
            ["Desert Palace - Compass Chest", false, [], ['KeyP2']],
            ["Desert Palace - Compass Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'DefeatAgahnim']],
            ["Desert Palace - Compass Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'ProgressiveGlove', 'Hammer']],
            ["Desert Palace - Compass Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'PowerGlove', 'Hammer']],
            ["Desert Palace - Compass Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove']],
            ["Desert Palace - Compass Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'TitansMitt']],

            ["Desert Palace - Big Key Chest", false, []],
            ["Desert Palace - Big Key Chest", false, [], ['BookOfMudora']],
            ["Desert Palace - Big Key Chest", false, [], ['MoonPearl']],
            ["Desert Palace - Big Key Chest", false, [], ['KeyP2']],
            ["Desert Palace - Big Key Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'DefeatAgahnim']],
            ["Desert Palace - Big Key Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'ProgressiveGlove', 'Hammer']],
            ["Desert Palace - Big Key Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'PowerGlove', 'Hammer']],
            ["Desert Palace - Big Key Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove']],
            ["Desert Palace - Big Key Chest", true, ['BookOfMudora', 'KeyP2', 'MoonPearl', 'TitansMitt']],

            ["Desert Palace - Boss", false, []],
            ["Desert Palace - Boss", false, [], ['KeyP2']],
            ["Desert Palace - Boss", false, [], ['BigKeyP2']],
            ["Desert Palace - Boss", false, [], ['BookOfMudora']],
            ["Desert Palace - Boss", false, [], ['MoonPearl']],
            ["Desert Palace - Boss", false, [], ['Gloves']],
            ["Desert Palace - Boss", false, [], ['Lamp', 'FireRod']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'ProgressiveGlove', 'DefeatAgahnim', 'KeyP2', 'BookOfMudora', 'Lamp', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'PowerGlove', 'DefeatAgahnim', 'KeyP2', 'BookOfMudora', 'Lamp', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['MoonPearl', 'ProgressiveGlove', 'Hammer', 'KeyP2', 'BookOfMudora', 'Lamp', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['MoonPearl', 'PowerGlove', 'Hammer', 'KeyP2', 'BookOfMudora', 'Lamp', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'KeyP2', 'BookOfMudora', 'Lamp', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'TitansMitt', 'KeyP2', 'BookOfMudora', 'Lamp', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'ProgressiveGlove', 'DefeatAgahnim', 'KeyP2', 'BookOfMudora', 'FireRod', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'PowerGlove', 'DefeatAgahnim', 'KeyP2', 'BookOfMudora', 'FireRod', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['MoonPearl', 'ProgressiveGlove', 'Hammer', 'KeyP2', 'BookOfMudora', 'FireRod', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['MoonPearl', 'PowerGlove', 'Hammer', 'KeyP2', 'BookOfMudora', 'FireRod', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'KeyP2', 'BookOfMudora', 'FireRod', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'TitansMitt', 'KeyP2', 'BookOfMudora', 'FireRod', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'MoonPearl', 'TitansMitt', 'KeyP2', 'BookOfMudora', 'FireRod', 'BigKeyP2']],
        ];
    }

    /**
     * @dataProvider dungeonItemsPool
     */
    public function testRegionLockedItems(bool $access, string $item_name, bool $free = null, string $config = null)
    {
        if ($config) {
            config([$config => $free]);
        }

        $this->assertEquals($access, $this->world->getRegion('Desert Palace')->canFill(Item::get($item_name, $this->world)));
    }

    public function dungeonItemsPool()
    {
        return [
            [true, 'Key'],
            [false, 'KeyH2'],
            [false, 'KeyH1'],
            [false, 'KeyP1'],
            [true, 'KeyP2'],
            [false, 'KeyA1'],
            [false, 'KeyD2'],
            [false, 'KeyD1'],
            [false, 'KeyD6'],
            [false, 'KeyD3'],
            [false, 'KeyD5'],
            [false, 'KeyP3'],
            [false, 'KeyD4'],
            [false, 'KeyD7'],
            [false, 'KeyA2'],

            [true, 'BigKey'],
            [false, 'BigKeyH2'],
            [false, 'BigKeyH1'],
            [false, 'BigKeyP1'],
            [true, 'BigKeyP2'],
            [false, 'BigKeyA1'],
            [false, 'BigKeyD2'],
            [false, 'BigKeyD1'],
            [false, 'BigKeyD6'],
            [false, 'BigKeyD3'],
            [false, 'BigKeyD5'],
            [false, 'BigKeyP3'],
            [false, 'BigKeyD4'],
            [false, 'BigKeyD7'],
            [false, 'BigKeyA2'],

            [true, 'Map', false, 'region.wildMaps'],
            [true, 'Map', true, 'region.wildMaps'],
            [false, 'MapH2', false, 'region.wildMaps'],
            [true, 'MapH2', true, 'region.wildMaps'],
            [false, 'MapH1', false, 'region.wildMaps'],
            [true, 'MapH1', true, 'region.wildMaps'],
            [false, 'MapP1', false, 'region.wildMaps'],
            [true, 'MapP1', true, 'region.wildMaps'],
            [true, 'MapP2', false, 'region.wildMaps'],
            [true, 'MapP2', true, 'region.wildMaps'],
            [false, 'MapA1', false, 'region.wildMaps'],
            [true, 'MapA1', true, 'region.wildMaps'],
            [false, 'MapD2', false, 'region.wildMaps'],
            [true, 'MapD2', true, 'region.wildMaps'],
            [false, 'MapD1', false, 'region.wildMaps'],
            [true, 'MapD1', true, 'region.wildMaps'],
            [false, 'MapD6', false, 'region.wildMaps'],
            [true, 'MapD6', true, 'region.wildMaps'],
            [false, 'MapD3', false, 'region.wildMaps'],
            [true, 'MapD3', true, 'region.wildMaps'],
            [false, 'MapD5', false, 'region.wildMaps'],
            [true, 'MapD5', true, 'region.wildMaps'],
            [false, 'MapP3', false, 'region.wildMaps'],
            [true, 'MapP3', true, 'region.wildMaps'],
            [false, 'MapD4', false, 'region.wildMaps'],
            [true, 'MapD4', true, 'region.wildMaps'],
            [false, 'MapD7', false, 'region.wildMaps'],
            [true, 'MapD7', true, 'region.wildMaps'],
            [false, 'MapA2', false, 'region.wildMaps'],
            [true, 'MapA2', true, 'region.wildMaps'],

            [true, 'Compass', false, 'region.wildCompasses'],
            [true, 'Compass', true, 'region.wildCompasses'],
            [false, 'CompassH2', false, 'region.wildCompasses'],
            [true, 'CompassH2', true, 'region.wildCompasses'],
            [false, 'CompassH1', false, 'region.wildCompasses'],
            [true, 'CompassH1', true, 'region.wildCompasses'],
            [false, 'CompassP1', false, 'region.wildCompasses'],
            [true, 'CompassP1', true, 'region.wildCompasses'],
            [true, 'CompassP2', false, 'region.wildCompasses'],
            [true, 'CompassP2', true, 'region.wildCompasses'],
            [false, 'CompassA1', false, 'region.wildCompasses'],
            [true, 'CompassA1', true, 'region.wildCompasses'],
            [false, 'CompassD2', false, 'region.wildCompasses'],
            [true, 'CompassD2', true, 'region.wildCompasses'],
            [false, 'CompassD1', false, 'region.wildCompasses'],
            [true, 'CompassD1', true, 'region.wildCompasses'],
            [false, 'CompassD6', false, 'region.wildCompasses'],
            [true, 'CompassD6', true, 'region.wildCompasses'],
            [false, 'CompassD3', false, 'region.wildCompasses'],
            [true, 'CompassD3', true, 'region.wildCompasses'],
            [false, 'CompassD5', false, 'region.wildCompasses'],
            [true, 'CompassD5', true, 'region.wildCompasses'],
            [false, 'CompassP3', false, 'region.wildCompasses'],
            [true, 'CompassP3', true, 'region.wildCompasses'],
            [false, 'CompassD4', false, 'region.wildCompasses'],
            [true, 'CompassD4', true, 'region.wildCompasses'],
            [false, 'CompassD7', false, 'region.wildCompasses'],
            [true, 'CompassD7', true, 'region.wildCompasses'],
            [false, 'CompassA2', false, 'region.wildCompasses'],
            [true, 'CompassA2', true, 'region.wildCompasses'],
        ];
    }
}
