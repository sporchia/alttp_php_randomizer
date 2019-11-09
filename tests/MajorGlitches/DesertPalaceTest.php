<?php

namespace MajorGlitches;

use ALttP\Item;
use ALttP\World;
use TestCase;

/**
 * @group MajorGlitches
 */
class DesertPalaceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->world = World::factory('standard', ['difficulty' => 'test_rules', 'logic' => 'MajorGlitches']);
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
            [true, ['BookOfMudora']],
            [true, ['Flute', 'MagicMirror', 'ProgressiveGlove', 'ProgressiveGlove']],
            [true, ['Flute', 'MagicMirror', 'TitansMitt']],
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
            ["Desert Palace - Map Chest", true, []],

            ["Desert Palace - Big Chest", false, []],
            ["Desert Palace - Big Chest", true, ['BookOfMudora', 'BigKeyP2']],
            ["Desert Palace - Big Chest", true, ['Flute', 'MagicMirror', 'ProgressiveGlove', 'ProgressiveGlove', 'BigKeyP2']],
            ["Desert Palace - Big Chest", true, ['Flute', 'MagicMirror', 'TitansMitt', 'BigKeyP2']],

            ["Desert Palace - Torch", false, []],
            ["Desert Palace - Torch", false, [], ['PegasusBoots']],
            ["Desert Palace - Torch", true, ['BookOfMudora', 'PegasusBoots']],
            ["Desert Palace - Torch", true, ['Flute', 'MagicMirror', 'ProgressiveGlove', 'ProgressiveGlove', 'PegasusBoots']],
            ["Desert Palace - Torch", true, ['Flute', 'MagicMirror', 'TitansMitt', 'PegasusBoots']],

            ["Desert Palace - Compass Chest", false, []],
            ["Desert Palace - Compass Chest", false, [], ['KeyP2']],
            ["Desert Palace - Compass Chest", true, ['BookOfMudora', 'KeyP2']],
            ["Desert Palace - Compass Chest", true, ['Flute', 'MagicMirror', 'ProgressiveGlove', 'ProgressiveGlove', 'KeyP2']],
            ["Desert Palace - Compass Chest", true, ['Flute', 'MagicMirror', 'TitansMitt', 'KeyP2']],

            ["Desert Palace - Big Key Chest", false, []],
            ["Desert Palace - Big Key Chest", false, [], ['KeyP2']],
            ["Desert Palace - Big Key Chest", true, ['BookOfMudora', 'KeyP2']],
            ["Desert Palace - Big Key Chest", true, ['Flute', 'MagicMirror', 'ProgressiveGlove', 'ProgressiveGlove', 'KeyP2']],
            ["Desert Palace - Big Key Chest", true, ['Flute', 'MagicMirror', 'TitansMitt', 'KeyP2']],

            ["Desert Palace - Boss", false, []],
            ["Desert Palace - Boss", false, [], ['BigKeyP2']],
            ["Desert Palace - Boss", false, [], ['Lamp', 'FireRod']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'BookOfMudora', 'Lamp', 'ProgressiveGlove', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'BookOfMudora', 'Lamp', 'PowerGlove', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'BookOfMudora', 'Lamp', 'TitansMitt', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'BookOfMudora', 'FireRod', 'ProgressiveGlove', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'BookOfMudora', 'FireRod', 'PowerGlove', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'BookOfMudora', 'FireRod', 'TitansMitt', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'Flute', 'MagicMirror', 'Lamp', 'ProgressiveGlove', 'ProgressiveGlove', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'Flute', 'MagicMirror', 'Lamp', 'TitansMitt', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'Flute', 'MagicMirror', 'FireRod', 'ProgressiveGlove', 'ProgressiveGlove', 'BigKeyP2']],
            ["Desert Palace - Boss", true, ['UncleSword', 'KeyP2', 'Flute', 'MagicMirror', 'FireRod', 'TitansMitt', 'BigKeyP2']],
        ];
    }
}
