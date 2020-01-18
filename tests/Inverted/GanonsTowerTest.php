<?php

namespace Inverted;

use ALttP\Item;
use ALttP\World;
use TestCase;

/**
 * @group Inverted
 */
class GanonsTowerTest extends TestCase
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
            ["Ganon's Tower - Bob's Torch", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - DMs Room - Top Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - DMs Room - Top Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - DMs Room - Bottom Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - DMs Room - Bottom Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Randomizer Room - Top Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Randomizer Room - Top Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Randomizer Room - Bottom Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Randomizer Room - Bottom Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Firesnake Room", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Map Chest", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Big Chest", false, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Hope Room - Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Hope Room - Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Bob's Chest", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Tile Room", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Compass Room - Top Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Compass Room - Top Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Compass Room - Bottom Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Compass Room - Bottom Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Big Key Chest", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Big Key Room - Left", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Big Key Room - Right", true, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Mini Helmasaur Room - Left", false, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Mini Helmasaur Room - Right", false, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Pre-Moldorm Chest", false, 'BigKeyA2', [], ['BigKeyA2']],

            ["Ganon's Tower - Moldorm Chest", false, 'BigKeyA2', [], ['BigKeyA2']],
        ];
    }

    public function accessPool()
    {
        return [
            ["Ganon's Tower - Bob's Torch", false, []],
            ["Ganon's Tower - Bob's Torch", false, [], ['MoonPearl']],
            ["Ganon's Tower - Bob's Torch", false, [], ['Crystal1']],
            ["Ganon's Tower - Bob's Torch", false, [], ['Crystal2']],
            ["Ganon's Tower - Bob's Torch", false, [], ['Crystal3']],
            ["Ganon's Tower - Bob's Torch", false, [], ['Crystal4']],
            ["Ganon's Tower - Bob's Torch", false, [], ['Crystal5']],
            ["Ganon's Tower - Bob's Torch", false, [], ['Crystal6']],
            ["Ganon's Tower - Bob's Torch", false, [], ['Crystal7']],
            ["Ganon's Tower - Bob's Torch", false, [], ['PegasusBoots']],
            ["Ganon's Tower - Bob's Torch", true, ['MoonPearl', 'DefeatAgahnim', 'PegasusBoots', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Torch", true, ['MoonPearl', 'ProgressiveGlove', 'Hammer', 'PegasusBoots', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Torch", true, ['MoonPearl', 'PowerGlove', 'Hammer', 'PegasusBoots', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Torch", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'PegasusBoots', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Torch", true, ['MoonPearl', 'TitansMitt', 'PegasusBoots', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - DMs Room - Top Left", false, []],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Crystal1']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Crystal2']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Crystal3']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Crystal4']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Crystal5']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Crystal6']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Crystal7']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Hammer']],
            ["Ganon's Tower - DMs Room - Top Left", false, [], ['Hookshot']],
            ["Ganon's Tower - DMs Room - Top Left", true, ['MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Left", true, ['MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Left", true, ['MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Left", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Left", true, ['MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - DMs Room - Top Right", false, []],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Crystal1']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Crystal2']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Crystal3']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Crystal4']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Crystal5']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Crystal6']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Crystal7']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Hammer']],
            ["Ganon's Tower - DMs Room - Top Right", false, [], ['Hookshot']],
            ["Ganon's Tower - DMs Room - Top Right", true, ['MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Right", true, ['MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Right", true, ['MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Right", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Top Right", true, ['MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - DMs Room - Bottom Left", false, []],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Crystal1']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Crystal2']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Crystal3']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Crystal4']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Crystal5']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Crystal6']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Hammer']],
            ["Ganon's Tower - DMs Room - Bottom Left", false, [], ['Hookshot']],
            ["Ganon's Tower - DMs Room - Bottom Left", true, ['MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Left", true, ['MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Left", true, ['MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Left", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Left", true, ['MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - DMs Room - Bottom Right", false, []],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Crystal1']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Crystal2']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Crystal3']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Crystal4']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Crystal5']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Crystal6']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Hammer']],
            ["Ganon's Tower - DMs Room - Bottom Right", false, [], ['Hookshot']],
            ["Ganon's Tower - DMs Room - Bottom Right", true, ['MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Right", true, ['MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Right", true, ['MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Right", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - DMs Room - Bottom Right", true, ['MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Randomizer Room - Top Left", false, []],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Crystal1']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Crystal2']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Crystal3']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Crystal4']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Crystal5']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Crystal6']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Hammer']],
            ["Ganon's Tower - Randomizer Room - Top Left", false, [], ['Hookshot']],
            ["Ganon's Tower - Randomizer Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Randomizer Room - Top Right", false, []],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Crystal1']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Crystal2']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Crystal3']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Crystal4']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Crystal5']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Crystal6']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Hammer']],
            ["Ganon's Tower - Randomizer Room - Top Right", false, [], ['Hookshot']],
            ["Ganon's Tower - Randomizer Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Randomizer Room - Bottom Left", false, []],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Crystal1']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Crystal2']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Crystal3']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Crystal4']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Crystal5']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Crystal6']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Hammer']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", false, [], ['Hookshot']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Randomizer Room - Bottom Right", false, []],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Crystal1']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Crystal2']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Crystal3']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Crystal4']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Crystal5']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Crystal6']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Hammer']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", false, [], ['Hookshot']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Randomizer Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Firesnake Room", false, []],
            ["Ganon's Tower - Firesnake Room", false, [], ['MoonPearl']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Crystal1']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Crystal2']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Crystal3']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Crystal4']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Crystal5']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Crystal6']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Crystal7']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Hammer']],
            ["Ganon's Tower - Firesnake Room", false, [], ['Hookshot']],
            ["Ganon's Tower - Firesnake Room", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Firesnake Room", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Firesnake Room", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Firesnake Room", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Firesnake Room", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Map Chest", false, []],
            ["Ganon's Tower - Map Chest", false, [], ['MoonPearl']],
            ["Ganon's Tower - Map Chest", false, [], ['Crystal1']],
            ["Ganon's Tower - Map Chest", false, [], ['Crystal2']],
            ["Ganon's Tower - Map Chest", false, [], ['Crystal3']],
            ["Ganon's Tower - Map Chest", false, [], ['Crystal4']],
            ["Ganon's Tower - Map Chest", false, [], ['Crystal5']],
            ["Ganon's Tower - Map Chest", false, [], ['Crystal6']],
            ["Ganon's Tower - Map Chest", false, [], ['Crystal7']],
            ["Ganon's Tower - Map Chest", false, [], ['Hammer']],
            ["Ganon's Tower - Map Chest", false, [], ['Hookshot', 'PegasusBoots']],
            ["Ganon's Tower - Map Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Map Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Map Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Map Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Map Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Big Chest", false, []],
            ["Ganon's Tower - Big Chest", false, [], ['MoonPearl']],
            ["Ganon's Tower - Big Chest", false, [], ['Crystal1']],
            ["Ganon's Tower - Big Chest", false, [], ['Crystal2']],
            ["Ganon's Tower - Big Chest", false, [], ['Crystal3']],
            ["Ganon's Tower - Big Chest", false, [], ['Crystal4']],
            ["Ganon's Tower - Big Chest", false, [], ['Crystal5']],
            ["Ganon's Tower - Big Chest", false, [], ['Crystal6']],
            ["Ganon's Tower - Big Chest", false, [], ['Crystal7']],
            ["Ganon's Tower - Big Chest", false, [], ['BigKeyA2']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Chest", true, ['BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Hope Room - Left", false, []],
            ["Ganon's Tower - Hope Room - Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - Hope Room - Left", false, [], ['Crystal1']],
            ["Ganon's Tower - Hope Room - Left", false, [], ['Crystal2']],
            ["Ganon's Tower - Hope Room - Left", false, [], ['Crystal3']],
            ["Ganon's Tower - Hope Room - Left", false, [], ['Crystal4']],
            ["Ganon's Tower - Hope Room - Left", false, [], ['Crystal5']],
            ["Ganon's Tower - Hope Room - Left", false, [], ['Crystal6']],
            ["Ganon's Tower - Hope Room - Left", false, [], ['Crystal7']],
            ["Ganon's Tower - Hope Room - Left", true, ['MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Left", true, ['MoonPearl', 'ProgressiveGlove', 'Hammer', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Left", true, ['MoonPearl', 'PowerGlove', 'Hammer', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Left", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Left", true, ['MoonPearl', 'TitansMitt', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Hope Room - Right", false, []],
            ["Ganon's Tower - Hope Room - Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - Hope Room - Right", false, [], ['Crystal1']],
            ["Ganon's Tower - Hope Room - Right", false, [], ['Crystal2']],
            ["Ganon's Tower - Hope Room - Right", false, [], ['Crystal3']],
            ["Ganon's Tower - Hope Room - Right", false, [], ['Crystal4']],
            ["Ganon's Tower - Hope Room - Right", false, [], ['Crystal5']],
            ["Ganon's Tower - Hope Room - Right", false, [], ['Crystal6']],
            ["Ganon's Tower - Hope Room - Right", true, ['MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Right", true, ['MoonPearl', 'ProgressiveGlove', 'Hammer', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Right", true, ['MoonPearl', 'PowerGlove', 'Hammer', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Right", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Hope Room - Right", true, ['MoonPearl', 'TitansMitt', 'Hookshot', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Bob's Chest", false, []],
            ["Ganon's Tower - Bob's Chest", false, [], ['MoonPearl']],
            ["Ganon's Tower - Bob's Chest", false, [], ['Crystal1']],
            ["Ganon's Tower - Bob's Chest", false, [], ['Crystal2']],
            ["Ganon's Tower - Bob's Chest", false, [], ['Crystal3']],
            ["Ganon's Tower - Bob's Chest", false, [], ['Crystal4']],
            ["Ganon's Tower - Bob's Chest", false, [], ['Crystal5']],
            ["Ganon's Tower - Bob's Chest", false, [], ['Crystal6']],
            ["Ganon's Tower - Bob's Chest", false, [], ['Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Bob's Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Tile Room", false, []],
            ["Ganon's Tower - Tile Room", false, [], ['MoonPearl']],
            ["Ganon's Tower - Tile Room", false, [], ['Crystal1']],
            ["Ganon's Tower - Tile Room", false, [], ['Crystal2']],
            ["Ganon's Tower - Tile Room", false, [], ['Crystal3']],
            ["Ganon's Tower - Tile Room", false, [], ['Crystal4']],
            ["Ganon's Tower - Tile Room", false, [], ['Crystal5']],
            ["Ganon's Tower - Tile Room", false, [], ['Crystal6']],
            ["Ganon's Tower - Tile Room", false, [], ['Crystal7']],
            ["Ganon's Tower - Tile Room", false, [], ['CaneOfSomaria']],
            ["Ganon's Tower - Tile Room", true, ['MoonPearl', 'DefeatAgahnim', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Tile Room", true, ['MoonPearl', 'ProgressiveGlove', 'Hammer', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Tile Room", true, ['MoonPearl', 'PowerGlove', 'Hammer', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Tile Room", true, ['MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Tile Room", true, ['MoonPearl', 'TitansMitt', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Compass Room - Top Left", false, []],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['Crystal1']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['Crystal2']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['Crystal3']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['Crystal4']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['Crystal5']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['Crystal6']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['Crystal7']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['CaneOfSomaria']],
            ["Ganon's Tower - Compass Room - Top Left", false, [], ['FireRod']],
            ["Ganon's Tower - Compass Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Compass Room - Top Right", false, []],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['Crystal1']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['Crystal2']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['Crystal3']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['Crystal4']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['Crystal5']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['Crystal6']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['Crystal7']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['CaneOfSomaria']],
            ["Ganon's Tower - Compass Room - Top Right", false, [], ['FireRod']],
            ["Ganon's Tower - Compass Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Top Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Compass Room - Bottom Left", false, []],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['Crystal1']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['Crystal2']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['Crystal3']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['Crystal4']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['Crystal5']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['Crystal6']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['CaneOfSomaria']],
            ["Ganon's Tower - Compass Room - Bottom Left", false, [], ['FireRod']],
            ["Ganon's Tower - Compass Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Compass Room - Bottom Right", false, []],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['Crystal1']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['Crystal2']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['Crystal3']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['Crystal4']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['Crystal5']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['Crystal6']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['CaneOfSomaria']],
            ["Ganon's Tower - Compass Room - Bottom Right", false, [], ['FireRod']],
            ["Ganon's Tower - Compass Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Compass Room - Bottom Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'FireRod', 'CaneOfSomaria', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Big Key Chest", false, []],
            ["Ganon's Tower - Big Key Chest", false, [], ['MoonPearl']],
            ["Ganon's Tower - Big Key Chest", false, [], ['Crystal1']],
            ["Ganon's Tower - Big Key Chest", false, [], ['Crystal2']],
            ["Ganon's Tower - Big Key Chest", false, [], ['Crystal3']],
            ["Ganon's Tower - Big Key Chest", false, [], ['Crystal4']],
            ["Ganon's Tower - Big Key Chest", false, [], ['Crystal5']],
            ["Ganon's Tower - Big Key Chest", false, [], ['Crystal6']],
            ["Ganon's Tower - Big Key Chest", false, [], ['Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Chest", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Big Key Room - Left", false, []],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['Crystal1']],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['Crystal2']],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['Crystal3']],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['Crystal4']],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['Crystal5']],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['Crystal6']],
            ["Ganon's Tower - Big Key Room - Left", false, [], ['Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Left", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Big Key Room - Right", false, []],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['Crystal1']],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['Crystal2']],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['Crystal3']],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['Crystal4']],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['Crystal5']],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['Crystal6']],
            ["Ganon's Tower - Big Key Room - Right", false, [], ['Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['UncleSword', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'CaneOfSomaria', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Big Key Room - Right", true, ['KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Hammer', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Mini Helmasaur Room - Left", false, []],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['MoonPearl']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['Crystal1']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['Crystal2']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['Crystal3']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['Crystal4']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['Crystal5']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['Crystal6']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['AnyBow']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", false, [], ['BigKeyA2']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Left", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Mini Helmasaur Room - Right", false, []],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['MoonPearl']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['Crystal1']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['Crystal2']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['Crystal3']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['Crystal4']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['Crystal5']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['Crystal6']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['AnyBow']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", false, [], ['BigKeyA2']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Mini Helmasaur Room - Right", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Pre-Moldorm Chest", false, []],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['MoonPearl']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['Crystal1']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['Crystal2']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['Crystal3']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['Crystal4']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['Crystal5']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['Crystal6']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['AnyBow']],
            ["Ganon's Tower - Pre-Moldorm Chest", false, [], ['BigKeyA2']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Pre-Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],

            ["Ganon's Tower - Moldorm Chest", false, []],
            ["Ganon's Tower - Moldorm Chest", false, [], ['MoonPearl']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Crystal1']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Crystal2']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Crystal3']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Crystal4']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Crystal5']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Crystal6']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Crystal7']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['Hookshot']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['AnyBow']],
            ["Ganon's Tower - Moldorm Chest", false, [], ['BigKeyA2']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'UncleSword', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'Hookshot', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'Hookshot', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'UncleSword', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'UncleSword', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'Lamp', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'UncleSword', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'DefeatAgahnim', 'Hookshot', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'Hammer', 'Hookshot', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'PowerGlove', 'Hammer', 'Hookshot', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'UncleSword', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'ProgressiveGlove', 'ProgressiveGlove', 'Hookshot', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
            ["Ganon's Tower - Moldorm Chest", true, ['BowAndArrows', 'UncleSword', 'BigKeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'KeyA2', 'MoonPearl', 'TitansMitt', 'Hookshot', 'FireRod', 'Crystal1', 'Crystal2', 'Crystal3', 'Crystal4', 'Crystal5', 'Crystal6', 'Crystal7']],
        ];
    }
}
