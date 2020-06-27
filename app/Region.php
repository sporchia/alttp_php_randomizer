<?php

namespace ALttP;

use ALttP\Support\ShopCollection;

/**
 * A logical collection of Locations. Can have special can_enter function that
 * will apply to all locaitons contained, and can_complete function set to
 * validate that the region prize (if set) can be obtained.
 */
class Region
{
    protected $locations;
    protected $shops;
    protected $can_enter;
    protected $can_complete;
    protected $name = 'Unknown';
    protected $prize_location;
    protected $world;
    protected $region_items = [];
    protected $boss = null;

    protected $map_reveal = 0x0000;

    /**
     * Create a new Region.
     *
     * @param World $world World this Region is part of
     *
     * @return void
     */
    public function __construct(World $world)
    {
        $this->world = $world;
        $this->shops = new ShopCollection;

        // hydrate region items.
        foreach ($this->region_items as $key => $item) {
            $this->region_items[$key] = Item::get($item, $world);
        }
    }

    /**
     * Get the World associated with this Region.
     *
     * @return World
     */
    public function getWorld()
    {
        return $this->world;
    }

    /**
     * Get the name of this Region.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the Boss of this Region.
     *
     * @param string  $level  which boss
     *
     * @return Boss
     */
    public function getBoss(string $level)
    {
        return $this->boss;
    }

    /**
     * Set the Boss of this Region.
     *
     * @param Boss $boss boss of the region
     *
     * @return $this
     */
    public function setBoss(Boss $boss, string $level = null): self
    {
        $this->boss = $boss;

        return $this;
    }

    /**
     * Check if a Boss can be placed in this region.
     * currently Agahnim or Ganon can't be moved.
     *
     * @param Boss $boss boss we are testing
     *
     * @return bool
     */
    public function canPlaceBoss(Boss $boss, string $level = 'top'): bool
    {
        return !in_array($boss->getName(), [
            "Agahnim",
            "Agahnim2",
            "Ganon",
        ]);
    }

    /**
     * Get the map reveal word for this region
     *
     * @return int
     */
    public function getMapReveal(): int
    {
        return $this->map_reveal;
    }

    /**
     * Set the Prize Location for completeing this Region and set it's rules for access to completing the region.
     *
     * @param Location\Prize $location location to put item that will be the prize
     *
     * @return $this
     */
    public function setPrizeLocation(Location\Prize $location)
    {
        $this->prize_location = $location;

        $this->prize_location->setRegion($this);
        if ($this->can_complete) {
            $this->prize_location->setRequirements($this->can_complete);
        }

        return $this;
    }

    /**
     * Get the Prize Location for completeing this Region.
     *
     * @return Location\Prize|null
     */
    public function getPrizeLocation()
    {
        return $this->prize_location;
    }

    /**
     * Get the Prize for completeing this Region.
     *
     * @return Item|null
     */
    public function getPrize()
    {
        if (!isset($this->prize_location) || !$this->prize_location->hasItem()) {
            return null;
        }

        return $this->prize_location->getItem();
    }

    /**
     * Determine if a (particular) Prize Item is set for the Region
     *
     * @param Item|null $item Item to check against
     *
     * @return bool
     */
    public function hasPrize(Item $item = null)
    {
        if (!isset($this->prize_location) || !$this->prize_location->hasItem()) {
            return false;
        }

        return $this->prize_location->hasItem($item);
    }

    /**
     * Initalize No logic for the Region
     *
     * @return $this
     */
    public function initalize()
    {
        return $this;
    }

    /**
     * Determine if the Region is completable given the locations and items available
     *
     * @param \ALttP\Support\LocationCollection $locations current list of Locations
     * @param \ALttP\Support\ItemCollection     $items     current list of Items collected
     *
     * @return bool
     */
    public function canComplete($locations, $items)
    {
        if ($this->can_complete) {
            return call_user_func($this->can_complete, $locations, $items);
        }
        return true;
    }

    /**
     * Determine if the Region can be entered given the locations and items available
     *
     * @param \ALttP\Support\LocationCollection $locations current list of Locations
     * @param \ALttP\Support\ItemCollection     $items     current list of Items collected
     *
     * @return bool
     */
    public function canEnter($locations, $items)
    {
        if ($this->can_enter) {
            return call_user_func($this->can_enter, $locations, $items);
        }
        return true;
    }

    /**
     * Determine if the item being placed in this region can be placed here.
     *
     * @param Item $item item to test
     *
     * @return bool
     */
    public function canFill(Item $item): bool
    {
        $from_world = $item->getWorld();
        if (((!$from_world->config('region.wildKeys', false) && $item instanceof Item\Key)
                || (!$from_world->config('region.wildBigKeys', false) && $item instanceof Item\BigKey)
                || ($item == Item::get('KeyH2', $from_world) && $from_world->config('mode.state') == 'standard') // Sewers Key cannot leave
                || (!$from_world->config('region.wildMaps', false) && $item instanceof Item\Map)
                || (!$from_world->config('region.wildCompasses', false) && $item instanceof Item\Compass))
            && !in_array($item, $this->region_items)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the item belongs to this region.
     *
     * @param Item $item item to test
     *
     * @return bool
     */
    public function isRegionItem(Item $item): bool
    {
        return in_array($item, $this->region_items);
    }

    /**
     * Get all the Locations in this Region
     *
     * @return Support\LocationCollection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Get Location in this Region by name
     *
     * @param string $name name of the Location
     *
     * @return Location
     */
    public function getLocation(string $name)
    {
        return $this->locations[$name];
    }

    /**
     * Get all the Locations in this Region that do not have an Item assigned
     *
     * @return Support\LocationCollection
     */
    public function getEmptyLocations()
    {
        return $this->locations->filter(function ($location) {
            return !$location->hasItem();
        });
    }

    /**
     * Get all the Locations in this Region that have (a particular) Item assigned to them
     *
     * @param Item $item item to search locations for
     *
     * @return Support\LocationCollection
     */
    public function locationsWithItem(Item $item = null)
    {
        return $this->locations->locationsWithItem($item);
    }

    /**
     * Get all the Shops in this Region
     *
     * @return Support\ShopCollection
     */
    public function getShops()
    {
        return $this->shops;
    }

    /**
     * Get Shop in this Region by name
     *
     * @param string $name name of the Shop
     *
     * @return Shop
     */
    public function getShop(string $name)
    {
        return $this->shops[$name];
    }
}
