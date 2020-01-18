<?php

namespace Inverted;

use ALttP\Item;
use ALttP\World;
use TestCase;

/**
 * @group Inverted
 */
class SkullWoodsTest extends TestCase
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

    /**
     * @param string $location
     * @param bool $access
     * @param string $item
     * @param array $items
     * @param array $except
     * @param array $keys
     * @param string $big_key
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
            ["Skull Woods - Big Chest", true, 'BigKeyD3', [], ['BigKeyD3']],

            ["Skull Woods - Big Key Chest", true, 'BigKeyD3', [], ['BigKeyD3']],

            ["Skull Woods - Compass Chest", true, 'BigKeyD3', [], ['BigKeyD3']],

            ["Skull Woods - Map Chest", true, 'BigKeyD3', [], ['BigKeyD3']],

            ["Skull Woods - Bridge Room", true, 'BigKeyD3', [], ['BigKeyD3']],

            ["Skull Woods - Pot Prison", true, 'BigKeyD3', [], ['BigKeyD3']],

            ["Skull Woods - Pinball Room", true, 'KeyD3', [], ['KeyD3']],

            ["Skull Woods - Boss", true, 'BigKeyD3', [], ['BigKeyD3']],
            ["Skull Woods - Boss", false, 'KeyD3', [], ['KeyD3']],
        ];
    }

    public function accessPool()
    {
        return [
            ["Skull Woods - Big Chest", false, []],
            ["Skull Woods - Big Chest", false, [], ['BigKeyD3']],
            ["Skull Woods - Big Chest", true, ['BigKeyD3']],

            ["Skull Woods - Big Key Chest", true, []],

            ["Skull Woods - Compass Chest", true, []],

            ["Skull Woods - Map Chest", true, []],

            ["Skull Woods - Bridge Room", false, []],
            ["Skull Woods - Bridge Room", false, [], ['FireRod']],
            ["Skull Woods - Bridge Room", true, ['FireRod']],

            ["Skull Woods - Pot Prison", true, []],

            ["Skull Woods - Pinball Room", true, []],

            ["Skull Woods - Boss", false, []],
            ["Skull Woods - Boss", false, [], ['FireRod']],
            ["Skull Woods - Boss", false, [], ['AnySword']],
            ["Skull Woods - Boss", true, ['KeyD3', 'KeyD3', 'KeyD3', 'FireRod', 'UncleSword']],
            ["Skull Woods - Boss", true, ['KeyD3', 'KeyD3', 'KeyD3', 'FireRod', 'MasterSword']],
            ["Skull Woods - Boss", true, ['KeyD3', 'KeyD3', 'KeyD3', 'FireRod', 'L3Sword']],
            ["Skull Woods - Boss", true, ['KeyD3', 'KeyD3', 'KeyD3', 'FireRod', 'L4Sword']],
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

        $this->assertEquals($access, $this->world->getRegion('Skull Woods')->canFill(Item::get($item_name, $this->world)));
    }

    public function dungeonItemsPool()
    {
        return [
            [true, 'Key'],
            [false, 'KeyH2'],
            [false, 'KeyH1'],
            [false, 'KeyP1'],
            [false, 'KeyP2'],
            [false, 'KeyA1'],
            [false, 'KeyD2'],
            [false, 'KeyD1'],
            [false, 'KeyD6'],
            [true, 'KeyD3'],
            [false, 'KeyD5'],
            [false, 'KeyP3'],
            [false, 'KeyD4'],
            [false, 'KeyD7'],
            [false, 'KeyA2'],

            [true, 'BigKey'],
            [false, 'BigKeyH2'],
            [false, 'BigKeyH1'],
            [false, 'BigKeyP1'],
            [false, 'BigKeyP2'],
            [false, 'BigKeyA1'],
            [false, 'BigKeyD2'],
            [false, 'BigKeyD1'],
            [false, 'BigKeyD6'],
            [true, 'BigKeyD3'],
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
            [false, 'MapP2', false, 'region.wildMaps'],
            [true, 'MapP2', true, 'region.wildMaps'],
            [false, 'MapA1', false, 'region.wildMaps'],
            [true, 'MapA1', true, 'region.wildMaps'],
            [false, 'MapD2', false, 'region.wildMaps'],
            [true, 'MapD2', true, 'region.wildMaps'],
            [false, 'MapD1', false, 'region.wildMaps'],
            [true, 'MapD1', true, 'region.wildMaps'],
            [false, 'MapD6', false, 'region.wildMaps'],
            [true, 'MapD6', true, 'region.wildMaps'],
            [true, 'MapD3', false, 'region.wildMaps'],
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
            [false, 'CompassP2', false, 'region.wildCompasses'],
            [true, 'CompassP2', true, 'region.wildCompasses'],
            [false, 'CompassA1', false, 'region.wildCompasses'],
            [true, 'CompassA1', true, 'region.wildCompasses'],
            [false, 'CompassD2', false, 'region.wildCompasses'],
            [true, 'CompassD2', true, 'region.wildCompasses'],
            [false, 'CompassD1', false, 'region.wildCompasses'],
            [true, 'CompassD1', true, 'region.wildCompasses'],
            [false, 'CompassD6', false, 'region.wildCompasses'],
            [true, 'CompassD6', true, 'region.wildCompasses'],
            [true, 'CompassD3', false, 'region.wildCompasses'],
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
