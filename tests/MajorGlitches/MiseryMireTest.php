<?php

namespace MajorGlitches;

use ALttP\Item;
use ALttP\World;
use TestCase;

/**
 * @group MajorGlitches
 */
class MiseryMireTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->world = World::factory('standard', ['difficulty' => 'test_rules', 'logic' => 'MajorGlitches']);
        $this->world->getLocation("Misery Mire Medallion")->setItem(Item::get('Ether', $this->world));
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
            ["Misery Mire - Big Chest", false, 'BigKeyD6', [], ['BigKeyD6']],

            ["Misery Mire - Main Lobby", true, 'BigKeyD6', [], ['BigKeyD6']],

            ["Misery Mire - Big Key Chest", true, 'BigKeyD6', [], ['BigKeyD6']],

            ["Misery Mire - Compass Chest", true, 'BigKeyD6', [], ['BigKeyD6']],

            ["Misery Mire - Bridge Chest", true, 'BigKeyD6', [], ['BigKeyD6']],

            ["Misery Mire - Map Chest", true, 'BigKeyD6', [], ['BigKeyD6']],

            ["Misery Mire - Spike Chest", true, 'BigKeyD6', [], ['BigKeyD6']],

            ["Misery Mire - Boss", false, 'BigKeyD6', [], ['BigKeyD6']],
        ];
    }

    public function accessPool()
    {
        return [
            ["Misery Mire - Big Chest", false, []],
            ["Misery Mire - Big Chest", false, [], ['BigKeyD6']],
            ["Misery Mire - Big Chest", false, [], ['Ether']],
            ["Misery Mire - Big Chest", false, [], ['MoonPearl', 'AnyBottle']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Big Chest", true, ['BigKeyD6', 'Bottle', 'Ether', 'L4Sword', 'Hookshot']],

            ["Misery Mire - Main Lobby", false, []],
            ["Misery Mire - Main Lobby", false, [], ['Ether']],
            ["Misery Mire - Main Lobby", false, [], ['MoonPearl', 'AnyBottle']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Main Lobby", true, ['KeyD6', 'Bottle', 'Ether', 'L4Sword', 'Hookshot']],

            ["Misery Mire - Big Key Chest", false, []],
            ["Misery Mire - Big Key Chest", false, [], ['FireRod', 'Lamp']],
            ["Misery Mire - Big Key Chest", false, [], ['Ether']],
            ["Misery Mire - Big Key Chest", false, [], ['MoonPearl', 'AnyBottle']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Big Key Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L4Sword', 'Hookshot']],

            ["Misery Mire - Compass Chest", false, []],
            ["Misery Mire - Compass Chest", false, [], ['FireRod', 'Lamp']],
            ["Misery Mire - Compass Chest", false, [], ['Ether']],
            ["Misery Mire - Compass Chest", false, [], ['MoonPearl', 'AnyBottle']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'Lamp', 'Bottle', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Compass Chest", true, ['KeyD6', 'KeyD6', 'KeyD6', 'FireRod', 'Bottle', 'Ether', 'L4Sword', 'Hookshot']],

            ["Misery Mire - Bridge Chest", false, []],
            ["Misery Mire - Bridge Chest", false, [], ['Ether']],
            ["Misery Mire - Bridge Chest", false, [], ['MoonPearl', 'AnyBottle']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Bridge Chest", true, ['Bottle', 'Ether', 'L4Sword', 'Hookshot']],

            ["Misery Mire - Map Chest", false, []],
            ["Misery Mire - Map Chest", false, [], ['Ether']],
            ["Misery Mire - Map Chest", false, [], ['MoonPearl', 'AnyBottle']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Map Chest", true, ['KeyD6', 'Bottle', 'Ether', 'L4Sword', 'Hookshot']],

            ["Misery Mire - Spike Chest", false, []],
            ["Misery Mire - Spike Chest", false, [], ['Ether']],
            ["Misery Mire - Spike Chest", false, [], ['MoonPearl', 'AnyBottle']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['MoonPearl', 'Ether', 'L4Sword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Spike Chest", true, ['Bottle', 'Ether', 'L4Sword', 'Hookshot']],

            ["Misery Mire - Boss", false, []],
            ["Misery Mire - Boss", false, [], ['Lamp']],
            ["Misery Mire - Boss", false, [], ['CaneOfSomaria']],
            ["Misery Mire - Boss", false, [], ['BigKeyD6']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'UncleSword', 'PegasusBoots']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'UncleSword', 'Hookshot']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'ProgressiveSword', 'PegasusBoots']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'ProgressiveSword', 'Hookshot']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'MasterSword', 'PegasusBoots']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'MasterSword', 'Hookshot']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'L3Sword', 'PegasusBoots']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'L3Sword', 'Hookshot']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'L4Sword', 'PegasusBoots']],
            ["Misery Mire - Boss", true, ['KeyD6', 'KeyD6', 'BigKeyD6', 'Lamp', 'CaneOfSomaria', 'MoonPearl', 'Flute', 'TitansMitt', 'Ether', 'L4Sword', 'Hookshot']],

        ];
    }
}
