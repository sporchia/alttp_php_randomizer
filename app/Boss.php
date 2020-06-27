<?php

namespace ALttP;

use ALttP\Support\BossCollection;
use ALttP\Support\ItemCollection;
use ALttP\Support\LocationCollection;

/**
 * Boss Logic for beating each boss
 */
class Boss
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $enemizer_name;
    /** @var callable|null */
    protected $can_beat;
    /** @var array */
    protected static $items;
    /** @var array */
    protected static $worlds = [];

    /**
     * Get the Boss by name
     *
     * @param string $name Name of Boss
     * @param \ALttP\World  $world  World boss belongs to
     *
     * @throws \Exception if the Boss doesn't exist
     *
     * @return \ALttP\Boss
     */
    public static function get(string $name, World $world): Boss
    {
        $items = static::all($world);
        if (isset($items[$name])) {
            return $items[$name];
        }

        throw new \Exception('Unknown Boss: ' . $name);
    }

    /**
     * Clears the internal cache so we don't leak memory in testing.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        static::$items = [];
        static::$worlds = [];
    }

    /**
     * Get the all known Bosses
     *
     * @return \ALttP\Support\BossCollection
     */
    public static function all(World $world): BossCollection
    {
        if (isset(static::$items[$world->id])) {
            return static::$items[$world->id];
        }
        static::$worlds[$world->id] = $world;

        static::$items[$world->id] = new BossCollection([
            new static("Armos Knights", "Armos", function ($locations, $items) use ($world) {
                return $items->hasSword() || $items->has('Hammer') || $items->canShootArrows($world)
                    || $items->has('Boomerang') || $items->has('RedBoomerang')
                    || ($items->canExtendMagic(4) && ($items->has('FireRod') || $items->has('IceRod')))
                    || ($items->canExtendMagic(2) && ($items->has('CaneOfByrna') || $items->has('CaneOfSomaria')));
            }),
            new static("Lanmolas", "Lanmola", function ($locations, $items) use ($world) {
                return $items->hasSword() || $items->has('Hammer')
                    || $items->canShootArrows($world) || $items->has('FireRod') || $items->has('IceRod')
                    || $items->has('CaneOfByrna') || $items->has('CaneOfSomaria');
            }),
            new static("Moldorm", "Moldorm", function ($locations, $items) {
                return $items->hasSword() || $items->has('Hammer');
            }),
            new static("Agahnim", "Agahnim", function ($locations, $items) {
                return $items->hasSword() || $items->has('Hammer') || $items->has('BugCatchingNet');
            }),
            new static("Helmasaur King", "Helmasaur", function ($locations, $items) use ($world) {
                return ($items->canBombThings() || $items->has('Hammer'))
                    && ($items->hasSword(2) || $items->canShootArrows($world));
            }),
            new static("Arrghus", "Arrghus", function ($locations, $items) use ($world) {
                return ($world->config('itemPlacement') !== 'basic' || $world->config('mode.weapons') === 'swordless' || $items->hasSword(2))
                    && $items->has('Hookshot') && ($items->has('Hammer') || $items->hasSword()
                        || (($items->canExtendMagic(2) || $items->canShootArrows($world)) && ($items->has('FireRod') || $items->has('IceRod'))));
            }),
            new static("Mothula", "Mothula", function ($locations, $items) use ($world) {
                return ($world->config('itemPlacement') !== 'basic' || $items->hasSword(2) || ($items->canExtendMagic(2) && $items->has('FireRod')))
                    && ($items->hasSword() || $items->has('Hammer')
                        || ($items->canExtendMagic(2) && ($items->has('FireRod') || $items->has('CaneOfSomaria')
                            || $items->has('CaneOfByrna')))
                        || $items->canGetGoodBee());
            }),
            new static("Blind", "Blind", function ($locations, $items) use ($world) {
                return ($world->config('itemPlacement') !== 'basic' || $world->config('mode.weapons') === 'swordless' || ($items->hasSword() && ($items->has('Cape') || $items->has('CaneOfByrna'))))
                    && ($items->hasSword() || $items->has('Hammer')
                        || $items->has('CaneOfSomaria') || $items->has('CaneOfByrna'));
            }),
            new static("Kholdstare", "Kholdstare", function ($locations, $items) use ($world) {
                return ($world->config('itemPlacement') !== 'basic' || $items->hasSword(2) || ($items->canExtendMagic(3) && $items->has('FireRod'))
                    || ($items->has('Bombos') && (($world->config('mode.weapons') === 'swordless' && $world->config('enemizer.bossShuffle') === 'none') || $items->hasSword()) && $items->canExtendMagic(2) && $items->has('FireRod')))
                    && ($items->has('FireRod') || ($items->has('Bombos') && (($world->config('mode.weapons') === 'swordless' && $world->config('enemizer.bossShuffle') === 'none') || $items->hasSword())))
                    && ($items->has('Hammer') || $items->hasSword()
                        || ($items->canExtendMagic(3) && $items->has('FireRod'))
                        || ($items->canExtendMagic(2) && $items->has('FireRod') && $items->has('Bombos') && ($world->config('mode.weapons') === 'swordless' && $world->config('enemizer.bossShuffle') === 'none')));
            }),
            new static("Vitreous", "Vitreous", function ($locations, $items) use ($world) {
                return ($world->config('itemPlacement') !== 'basic' || $items->hasSword(2) || $items->canShootArrows($world))
                    && ($items->has('Hammer') || $items->hasSword() || $items->canShootArrows($world));
            }),
            new static("Trinexx", "Trinexx", function ($locations, $items) use ($world) {
                return $items->has('FireRod') && $items->has('IceRod')
                    && ($world->config('itemPlacement') !== 'basic' || $world->config('mode.weapons') === 'swordless' || $items->hasSword(3) || ($items->canExtendMagic(2) && $items->hasSword(2)))
                    && ($items->hasSword(3) || $items->has('Hammer')
                        || ($items->canExtendMagic(2) && $items->hasSword(2))
                        || ($items->canExtendMagic(4) && $items->hasSword()));
            }),
            new static("Agahnim2", "Agahnim2", function ($locations, $items) {
                return $items->hasSword() || $items->has('Hammer') || $items->has('BugCatchingNet');
            }),
        ]);

        return static::all($world);
    }

    /**
     * Create a new Item.
     *
     * @param string         $name      Unique name of Boss
     * @param callable|null  $can_beat  Rules for beating the Boss
     *
     * @return void
     */
    public function __construct(string $name, string $ename = null, callable $can_beat = null)
    {
        $this->name = $name;
        $this->enemizer_name = $ename ?? $name;
        $this->can_beat = $can_beat;
    }

    /**
     * Get the name of this Boss.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the name of this Boss for Enemizer.
     *
     * @return string
     */
    public function getEName(): string
    {
        return $this->enemizer_name;
    }

    /**
     * Determine if Link can beat this Boss.
     *
     * @param \ALttP\Support\ItemCollection           $items      Items Link can collect
     * @param \ALttP\Support\LocationCollection|null  $locations
     *
     * @return bool
     */
    public function canBeat(ItemCollection $items, ?LocationCollection $locations = null): bool
    {
        if ($this->can_beat === null || call_user_func($this->can_beat, $locations ?? new LocationCollection, $items)) {
            return true;
        }

        return false;
    }
}
