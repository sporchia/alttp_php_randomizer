<?php

namespace ALttP;

use ALttP\Contracts\Randomizer as RandomizerContract;
use ALttP\Services\HintService;
use ALttP\Support\ItemCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Main class for randomization. All the magic happens here.
 */
class Randomizer implements RandomizerContract
{
    /**
     * This represents the logic for the Randmizer, if any locations logic gets
     * changed this should change as well.
     */
    const LOGIC = 31;
    /** @var array */
    protected $worlds = [];
    /** @var array */
    protected $advancement_items = [];
    /** @var array */
    protected $trash_items = [];
    /** @var array */
    protected $nice_items = [];
    /** @var array */
    protected $dungeon_items = [];

    /**
     * Create a new Randomizer.
     *
     * @param array  $worlds  worlds to randomize
     *
     * @return void
     */
    public function __construct(array $worlds)
    {
        foreach ($worlds as $world) {
            if (!$world instanceof World) {
                throw new \OutOfBoundsException;
            }

            if ($world->getPreCollectedItems()->count() === 0) {
                $world->setPreCollectedItems(new ItemCollection([
                    Item::get('BossHeartContainer', $world),
                    Item::get('BossHeartContainer', $world),
                    Item::get('BossHeartContainer', $world),
                    Item::get('BombUpgrade10', $world),
                    Item::get('ArrowUpgrade10', $world),
                    Item::get('ArrowUpgrade10', $world),
                    Item::get('ArrowUpgrade10', $world),
                ]));
            }
        }
        $this->worlds = $worlds;
    }

    /**
     * Fill all empty Locations with Items using logic from the World. This is
     * achieved by first setting up base portions of the world. Then taking the
     * remaining empty locations we order them, and try to fill them in order in
     * a way that opens more locations.
     *
     * @return void
     */
    public function randomize(): void
    {
        Log::info("Randomizing");

        $filler = Filler::factory('RandomAssumed', $this->worlds);

        foreach ($this->worlds as $world) {
            $this->prepareWorld($world);
        }

        $filler->fill($this->dungeon_items, $this->advancement_items, $this->nice_items, $this->trash_items);

        foreach ($this->worlds as $world) {
            $this->setTexts($world);
            $this->randomizeCredits($world);
        }

        (new HintService($this->worlds, $this->advancement_items))->applyHints();
    }

    /**
     * Setup world with base randomization. This modifies item pools for the
     * filler.
     *
     * @param \ALttP\World  $world  World to set up
     *
     * @return void
     */
    public function prepareWorld(World $world): void
    {
        switch ($world->config('goal')) {
            case 'pedestal':
                $world->getLocation("Master Sword Pedestal")->setItem(Item::get('Triforce', $world));
                break;
            case 'ganon':
            case 'fast_ganon':
            case 'dungeons':
                $world->getLocation("Ganon")->setItem(Item::get('Triforce', $world));
                break;
        }

        $dungeon_items = $world->getDungeonPool();
        $advancement_items = $world->getAdvancementItems();
        $nice_items = $world->getNiceItems();
        $trash_items = $world->getItemPool();

        // @todo check a flag instead of logic here, as well as difficulty
        if (in_array($world->config('logic'), ['MajorGlitches', 'OverworldGlitches', 'None']) && $world->config('difficulty') !== 'custom') {
            $world->addPreCollectedItem(Item::get('PegasusBoots', $world));
            foreach ($advancement_items as $key => $item) {
                if ($item == Item::get('PegasusBoots', $world)) {
                    unset($advancement_items[$key]);
                    array_push($trash_items, Item::get('TwentyRupees', $world));
                    break;
                }
            }
        }

        if ($world->config('mode.state') != 'standard') {
            $world->addPreCollectedItem(Item::get('RescueZelda', $world));
        }

        // Set up World before we fill dungeons
        $this->setShops($world);
        $this->setMedallions($world);
        $this->placeBosses($world);
        $this->fillPrizes($world);
        $this->setFountains($world);
        $this->shufflePrizePacks($world);

        $locations = $world->getLocations()->filter(function ($location) {
            return !is_a($location, Location\Prize::class)
                && !is_a($location, Location\Medallion::class);
        });

        $locations["Pyramid Fairy - Bow"]->setItem($world->config('region.pyramidBowUpgrade', false)
            ? Item::get('BowAndSilverArrows', $world)
            : Item::get('BowAndArrows', $world));

        // fill boss hearts before anything else if we need to
        if ($world->config('region.bossesHaveItem', false) || !$world->config('region.bossHeartsInPool', true)) {
            $boss_item = !$world->config('region.bossHeartsInPool', true)
                ? Item::get('BossHeartContainer', $world)
                : Item::get($world->config('region.bossesHaveItem'), $world);
            $locations["Desert Palace - Boss"]->setItem($boss_item);
            $locations["Eastern Palace - Boss"]->setItem($boss_item);
            $locations["Ice Palace - Boss"]->setItem($boss_item);
            $locations["Misery Mire - Boss"]->setItem($boss_item);
            $locations["Palace of Darkness - Boss"]->setItem($boss_item);
            $locations["Skull Woods - Boss"]->setItem($boss_item);
            $locations["Swamp Palace - Boss"]->setItem($boss_item);
            $locations["Thieves' Town - Boss"]->setItem($boss_item);
            $locations["Turtle Rock - Boss"]->setItem($boss_item);
            $locations["Tower of Hera - Boss"]->setItem($boss_item);
        }

        // take out all the swords and silver arrows, and sometimes heart pieces
        // and somtimes armors
        $nice_items_swords = [];
        $nice_items_bottles = [];
        $nice_items_health = [];
        $nice_items_armors = [];
        foreach ($advancement_items as $key => $item) {
            if ($item == Item::get('SilverArrowUpgrade', $world)) {
                $nice_items[] = $item;
                unset($advancement_items[$key]);
                continue;
            }
            if ($item instanceof Item\Sword) {
                $nice_items_swords[] = $item;
                unset($advancement_items[$key]);
                continue;
            }
            if ($item instanceof Item\Bottle) {
                $nice_items_bottles[] = $item;
                unset($advancement_items[$key]);
                continue;
            }
        }
        // and from the nice items as well
        foreach ($nice_items as $key => $item) {
            if ($item instanceof Item\Sword) {
                unset($nice_items[$key]);
                $nice_items_swords[] = $item;
            }
            if ($item instanceof Item\Upgrade\Health) {
                unset($nice_items[$key]);
                $nice_items_health[] = $item;
            }
            if ($item instanceof Item\Armor) {
                unset($nice_items[$key]);
                $nice_items_armors[] = $item;
            }
        }
        foreach ($trash_items as $key => $item) {
            if ($item instanceof Item\Upgrade\Health) {
                unset($trash_items[$key]);
                $nice_items_health[] = $item;
            }
        }

        if ($world->config('itemPlacement') === 'basic') {
            $advancement_items = array_merge($advancement_items, $nice_items_health);
            $advancement_items = array_merge($advancement_items, $nice_items_armors);
        } else {
            $nice_items = array_merge($nice_items, $nice_items_health);
            $nice_items = array_merge($nice_items, $nice_items_armors);
        }

        if ($world->config('mode.weapons') === 'swordless') {
            foreach ($nice_items_swords as $unneeded) {
                $nice_items[] = Item::get('TwentyRupees2', $world);
            }
            $world_items = $world->collectItems();
            // check for pregressive bows
            if (!$world_items->merge($advancement_items)->has('ProgressiveBow', 2)) {
                $world_items = $world_items->values();
                if (
                    !in_array(Item::get('SilverArrowUpgrade', $world), $world_items)
                    && !in_array(Item::get('BowAndSilverArrows', $world), $world_items)
                ) {
                    if (array_search(Item::get('SilverArrowUpgrade', $world), $nice_items) === false && $world->config('difficulty') !== 'custom') {
                        $advancement_items[] = Item::get('SilverArrowUpgrade', $world);
                    }
                }
            }
        } elseif ($world->config('mode.weapons') === 'vanilla') {
            $uncle_sword = Item::get('UncleSword', $world)->setTarget(array_pop($nice_items_swords));
            $world->getLocation("Link's Uncle")->setItem($uncle_sword);

            foreach (["Pyramid Fairy - Left", "Blacksmith"] as $location) {
                $world->getLocation($location)->setItem(array_pop($nice_items_swords));
            }
            if (!$world->getLocation("Master Sword Pedestal")->hasItem(Item::get('Triforce', $world))) {
                $world->getLocation("Master Sword Pedestal")->setItem(array_pop($nice_items_swords));
            } else {
                array_pop($nice_items_swords);
                array_push($trash_items, Item::get('TwentyRupees', $world));
            }
        } else {
            // put uncle sword back
            if (count($nice_items_swords)) {
                $uncle_sword = Item::get('UncleSword', $world)->setTarget(array_pop($nice_items_swords));
                if ($world->config('mode.weapons') === 'assured') {
                    $world->addPreCollectedItem($uncle_sword);
                    array_push($trash_items, Item::get('FiftyRupees', $world));
                } else {
                    array_push($advancement_items, $uncle_sword);
                }
            }

            // put master sword back in
            if (count($nice_items_swords)) {
                array_push($advancement_items, array_pop($nice_items_swords));
            }

            // put tempered sword back in if logically required
            if ($world->config('region.requireBetterSword', false) && count($nice_items_swords)) {
                array_push($advancement_items, array_pop($nice_items_swords));
            }

            if (count($nice_items_swords)) {
                if ($world->config('region.takeAnys', false)) {
                    array_pop($nice_items_swords);
                    array_push($trash_items, Item::get('TwentyRupees', $world));
                }
            }

            $nice_items = array_merge($nice_items, $nice_items_swords);
        }
        // put 1 bottle back
        if (count($nice_items_bottles)) {
            array_push($advancement_items, array_pop($nice_items_bottles));
        }
        $nice_items = array_merge($nice_items, $nice_items_bottles);

        if ($world->config('rom.rupeeBow', false)) {
            $trash_items_replace = [];
            foreach ($trash_items as $key => $item) {
                if ($item instanceof Item\Arrow || $item instanceof Item\Upgrade\Arrow) {
                    unset($trash_items[$key]);
                    $trash_items_replace[] = Item::get('FiveRupees', $world);
                }
            }
            $trash_items = array_merge($trash_items, $trash_items_replace);
        }

        // F this key
        if ($world->config('region.forceSkullWoodsKey', false)) {
            foreach ($dungeon_items as $key => $item) {
                if ($item === Item::get('KeyD3', $world)) {
                    unset($dungeon_items[$key]);
                    $locations["Skull Woods - Pinball Room"]->setItem($item);

                    break;
                }
            }
        }

        if ($world->config('region.wildBigKeys', false)) {
            foreach ($dungeon_items as $key => $item) {
                if ($item instanceof Item\BigKey) {
                    unset($dungeon_items[$key]);
                    $advancement_items[] = $item;
                }
            }
        }
        if ($world->config('region.wildKeys', false)) {
            foreach ($dungeon_items as $key => $item) {
                if ($item instanceof Item\Key && ($world->config('mode.state') != 'standard' || $item != Item::get('KeyH2', $world))) {
                    unset($dungeon_items[$key]);
                    $advancement_items[] = $item;
                }
            }
        }
        if ($world->config('region.wildMaps', false)) {
            foreach ($dungeon_items as $key => $item) {
                if ($item instanceof Item\Map) {
                    unset($dungeon_items[$key]);
                    $advancement_items[] = $item;
                }
            }
        }
        if ($world->config('region.wildCompasses', false)) {
            foreach ($dungeon_items as $key => $item) {
                if ($item instanceof Item\Compass) {
                    unset($dungeon_items[$key]);
                    $advancement_items[] = $item;
                }
            }
        }

        $this->dungeon_items = array_merge($this->dungeon_items, $dungeon_items);
        $this->advancement_items = fy_shuffle(array_merge($this->advancement_items, $advancement_items));
        $this->nice_items = fy_shuffle(array_merge($this->nice_items, $nice_items));
        $this->trash_items = fy_shuffle(array_merge($this->trash_items, $trash_items));
    }

    /**
     * Place the bosses for each region.
     *
     * @param World $world world to place bosses in.
     *
     * @return $this
     */
    public function placeBosses(World $world): self
    {
        // most restrictive first
        $boss_locations = [
            ['Ganons Tower', 'top'],
            ['Ganons Tower', 'middle'],
            ['Tower of Hera', ''],
            ['Skull Woods', ''],
            ['Eastern Palace', ''],
            ['Desert Palace', ''],
            ['Palace of Darkness', ''],
            ['Swamp Palace', ''],
            ['Thieves Town', ''],
            ['Ice Palace', ''],
            ['Misery Mire', ''],
            ['Turtle Rock', ''],
            ['Ganons Tower', 'bottom'],
        ];

        $placeable_bosses = Boss::all($world)->filter(function ($boss) use ($world) {
            return !in_array($boss->getName(), [
                "Agahnim",
                "Agahnim2",
                "Ganon",
            ]);
        });

        switch ($world->config('enemizer.bossShuffle')) {
            case 'random':
                foreach ($boss_locations as $location) {
                    do {
                        $boss = Boss::all($world)->random();
                    } while (!$world->getRegion($location[0])->canPlaceBoss($boss, $location[1]));
                    Log::debug((string) json_encode([$location[0], $location[1], $boss->getName()]));
                    $world->getRegion($location[0])->setBoss($boss, $location[1]);
                }
                break;
            case 'full': // 1 copy of each, +3 other copies
                $bosses = fy_shuffle(array_merge($placeable_bosses->values(), $placeable_bosses->randomCollection(3)->values()));
                foreach ($boss_locations as $location) {
                    $boss = array_shift($bosses);
                    while (!$world->getRegion($location[0])->canPlaceBoss($boss, $location[1])) {
                        array_push($bosses, $boss);
                        $boss = array_shift($bosses);
                    }
                    Log::debug((string) json_encode([$location[0], $location[1], $boss->getName()]));
                    $world->getRegion($location[0])->setBoss($boss, $location[1]);
                }
                break;
            case 'simple': // 1:1
                $bosses = fy_shuffle(array_merge($placeable_bosses->values(), [
                    Boss::get("Armos Knights", $world),
                    Boss::get("Lanmolas", $world),
                    Boss::get("Moldorm", $world),
                ]));
                foreach ($boss_locations as $location) {
                    $boss = array_shift($bosses);
                    while (!$world->getRegion($location[0])->canPlaceBoss($boss, $location[1])) {
                        array_push($bosses, $boss);
                        $boss = array_shift($bosses);
                    }
                    Log::debug((string) json_encode([$location[0], $location[1], $boss->getName()]));
                    $world->getRegion($location[0])->setBoss($boss, $location[1]);
                }
                break;
            case 'none':
            default:
                $world->getRegion('Eastern Palace')->setBoss(Boss::get("Armos Knights", $world));
                $world->getRegion('Desert Palace')->setBoss(Boss::get("Lanmolas", $world));
                $world->getRegion('Tower of Hera')->setBoss(Boss::get("Moldorm", $world));
                $world->getRegion('Palace of Darkness')->setBoss(Boss::get("Helmasaur King", $world));
                $world->getRegion('Swamp Palace')->setBoss(Boss::get("Arrghus", $world));
                $world->getRegion('Skull Woods')->setBoss(Boss::get("Mothula", $world));
                $world->getRegion('Thieves Town')->setBoss(Boss::get("Blind", $world));
                $world->getRegion('Ice Palace')->setBoss(Boss::get("Kholdstare", $world));
                $world->getRegion('Misery Mire')->setBoss(Boss::get("Vitreous", $world));
                $world->getRegion('Turtle Rock')->setBoss(Boss::get("Trinexx", $world));
                $world->getRegion('Ganons Tower')->setBoss(Boss::get("Armos Knights", $world), 'bottom');
                $world->getRegion('Ganons Tower')->setBoss(Boss::get("Lanmolas", $world), 'middle');
                $world->getRegion('Ganons Tower')->setBoss(Boss::get("Moldorm", $world), 'top');
        }

        $world->getRegion('Hyrule Castle Tower')->setBoss(Boss::get("Agahnim", $world));
        $world->getRegion('Ganons Tower')->setBoss(Boss::get("Agahnim2", $world));

        return $this;
    }

    /**
     * Place the prizes for dungeon completion. This is non-destructive.
     *
     * @param World $world world to fill prizes on.
     *
     * @return $this
     */
    public function fillPrizes(World $world, $attempts = 5): self
    {
        $prize_locations = $world->getLocations()->filter(function ($location) {
            return $location instanceof Location\Prize;
        })->randomCollection(15);

        $crystal_locations = $prize_locations->filter(function ($location) {
            return $location instanceof Location\Prize\Crystal;
        });

        $pendant_locations = $prize_locations->filter(function ($location) {
            return $location instanceof Location\Prize\Pendant;
        });

        if (!$world->config('prize.shuffleCrystals', true)) {
            $crystal_locations["Palace of Darkness - Prize"]->setItem(Item::get('Crystal1', $world));
            $crystal_locations["Swamp Palace - Prize"]->setItem(Item::get('Crystal2', $world));
            $crystal_locations["Skull Woods - Prize"]->setItem(Item::get('Crystal3', $world));
            $crystal_locations["Thieves' Town - Prize"]->setItem(Item::get('Crystal4', $world));
            $crystal_locations["Ice Palace - Prize"]->setItem(Item::get('Crystal5', $world));
            $crystal_locations["Misery Mire - Prize"]->setItem(Item::get('Crystal6', $world));
            $crystal_locations["Turtle Rock - Prize"]->setItem(Item::get('Crystal7', $world));
        }

        if (!$world->config('prize.shufflePendants', true)) {
            $pendant_locations["Eastern Palace - Prize"]->setItem(Item::get('PendantOfCourage', $world));
            $pendant_locations["Desert Palace - Prize"]->setItem(Item::get('PendantOfPower', $world));
            $pendant_locations["Tower of Hera - Prize"]->setItem(Item::get('PendantOfWisdom', $world));
        }

        $placed_prizes = $prize_locations->getItems();

        $remaining_prizes = fy_shuffle(array_diff([
            Item::get('Crystal1', $world),
            Item::get('Crystal2', $world),
            Item::get('Crystal3', $world),
            Item::get('Crystal4', $world),
            Item::get('Crystal5', $world),
            Item::get('Crystal6', $world),
            Item::get('Crystal7', $world),
            Item::get('PendantOfCourage', $world),
            Item::get('PendantOfPower', $world),
            Item::get('PendantOfWisdom', $world),
        ], $placed_prizes->values()));

        $place_prizes = ($world->config('prize.crossWorld', true))
            ? $remaining_prizes
            : array_filter($remaining_prizes, function ($item) {
                return $item instanceof Item\Crystal;
            });

        $empty_crystal_locations = $crystal_locations->getEmptyLocations();
        foreach ($empty_crystal_locations as $location) {
            $total_prizes = count($place_prizes);
            for ($i = 0; $i < $total_prizes; ++$i) {
                $place_prize = array_pop($place_prizes);
                $world->resetCollectedLocations();
                $assumed_items = $world->collectItems(new ItemCollection(array_merge(
                    $world->getDungeonPool(),
                    $world->getAdvancementItems(),
                    $place_prizes
                )));
                $assumed_items->setChecksForWorld($world->id);
                if ($location->canAccess($assumed_items)) {
                    break;
                }
                array_unshift($place_prizes, $place_prize);
            }
            if ($total_prizes == count($place_prizes)) {
                continue;
            }

            if (!isset($place_prize)) {
                continue;
            }

            if (!isset($assumed_items)) {
                continue;
            }

            $location->setItem($place_prize);
            Log::debug(sprintf("Placing: %s in %s", $location->getItem()->getNiceName(), $location->getName()));

            if (!$world->checkWinCondition($assumed_items)) {
                if ($attempts > 0) {
                    $empty_crystal_locations->each(function ($location) {
                        $location->setItem();
                    });
                    Log::debug(sprintf("D: Unwinnable Prize Placement (reset %s)", $attempts));
                    return $this->fillPrizes($world, $attempts - 1);
                }
                throw new \Exception("Cannot Place Prize: " . $location->getName());
            }
        }

        if ($crystal_locations->getEmptyLocations()->count()) {
            if ($attempts > 0) {
                $empty_crystal_locations->each(function ($location) {
                    $location->setItem();
                });
                Log::debug(sprintf("C: Unwinnable Prize Placement (reset %s)", $attempts));
                return $this->fillPrizes($world, $attempts - 1);
            }
            throw new \Exception("Cannot Place Prize: " . $crystal_locations->getEmptyLocations()->first()->getName());
        }

        $place_prizes = ($world->config('prize.crossWorld', true))
            ? $place_prizes
            : array_filter($remaining_prizes, function ($item) {
                return $item instanceof Item\Pendant;
            });

        $empty_pendant_locations = $pendant_locations->getEmptyLocations();
        foreach ($empty_pendant_locations as $location) {
            $total_prizes = count($place_prizes);
            for ($i = 0; $i < $total_prizes; ++$i) {
                $place_prize = array_pop($place_prizes);
                $world->resetCollectedLocations();
                $assumed_items = $world->collectItems(new ItemCollection(array_merge(
                    $world->getDungeonPool(),
                    $world->getAdvancementItems(),
                    $place_prizes
                )));
                $assumed_items->setChecksForWorld($world->id);
                if ($location->canAccess($assumed_items)) {
                    break;
                }
                array_unshift($place_prizes, $place_prize);
            }
            if ($total_prizes == count($place_prizes)) {
                continue;
            }

            if (!isset($place_prize)) {
                continue;
            }

            if (!isset($assumed_items)) {
                continue;
            }

            $location->setItem($place_prize);
            Log::debug(sprintf("Placing: %s in %s", $location->getItem()->getNiceName(), $location->getName()));

            if (!$world->checkWinCondition($assumed_items)) {
                if ($attempts > 0) {
                    $empty_pendant_locations->each(function ($location) {
                        $location->setItem();
                    });
                    Log::debug(sprintf("B: Unwinnable Prize Placement (reset %s)", $attempts));
                    return $this->fillPrizes($world, $attempts - 1);
                }
                throw new \Exception("Cannot Place Prize: " . $location->getName());
            }
        }
        if ($pendant_locations->getEmptyLocations()->count()) {
            if ($attempts > 0) {
                $empty_pendant_locations->each(function ($location) {
                    $location->setItem();
                });
                Log::debug(sprintf("A: Unwinnable Prize Placement (reset %s)", $attempts));
                return $this->fillPrizes($world, $attempts - 1);
            }
            throw new \Exception("Cannot Place Prize: " . $pendant_locations->getEmptyLocations()->first()->getName());
        }

        return $this;
    }

    /**
     * Randomize the Medallion requirements for a world.
     *
     * @param \ALttP\World  $world  world to adjust requirements in
     *
     * @return void
     */
    protected function setMedallions(World $world): void
    {
        $medallions = [
            Item::get('Ether', $world),
            Item::get('Bombos', $world),
            Item::get('Quake', $world),
        ];

        foreach ($world->getRegion('Medallions')->getLocations() as $medallion_location) {
            if ($medallion_location->hasItem()) {
                continue;
            }

            $medallion = $medallions[get_random_int(0, 2)];
            $medallion_location->setItem($medallion);
        }
    }

    /**
     * Randomize the fountain bottles for a world.
     *
     * @param \ALttP\World  $world  world to adjust bottle fountains
     *
     * @return void
     */
    protected function setFountains(World $world): void
    {
        foreach ($world->getRegion('Fountains')->getLocations() as $fountain) {
            if ($fountain->hasItem()) {
                continue;
            }

            $fountain->setItem($world->getBottle(true));
        }
    }

    /**
     * setup the shops for a given world.
     *
     * @param \ALttP\World $world world to set shops for
     *
     * @return void
     */
    protected function setShops(World $world): void
    {
        $shops = $world->getShops();

        $shops->filter(function ($shop) {
            return !$shop instanceof Shop\TakeAny;
        })->each(function ($shop) {
            $shop->setActive(true);
        });

        // handle hardmode shops
        switch ($world->config('rom.HardMode', 0)) {
            case 1:
            case 2:
            case 3:
                $world->getShop("Capacity Upgrade")->clearInventory();
                $world->getShop("Dark World Potion Shop")->addInventory(1, Item::get('Nothing', $world), 0);
                $world->getShop("Dark World Forest Shop")->addInventory(0, Item::get('Nothing', $world), 0);
                $world->getShop("Dark World Lumberjack Hut Shop")->addInventory(1, Item::get('Nothing', $world), 0);
                $world->getShop("Dark World Outcasts Shop")->addInventory(1, Item::get('Nothing', $world), 0);
                $world->getShop("Dark World Lake Hylia Shop")->addInventory(1, Item::get('Nothing', $world), 0);

                break;
        }

        if (
            !$world->config('rom.genericKeys', false)
            && !$world->config('rom.rupeeBow', false)
            && !$world->config('region.takeAnys', false)
        ) {
            return;
        }

        if ($world->config('region.takeAnys', false)) {
            $shops->filter(function ($shop) {
                return $shop instanceof Shop\TakeAny;
            })->randomCollection(4)->each(function ($shop) use ($world) {
                $shop->setActive(true);
                $shop->setShopkeeper('old_man');
                $shop->addInventory(0, Item::get('BluePotion', $world), 0);
                $shop->addInventory(1, Item::get('BossHeartContainer', $world), 0);
            });

            $old_man = $shops->filter(function ($shop) {
                return $shop instanceof Shop\TakeAny
                    && !$shop->getActive();
            })->random();

            $old_man->setActive(true);
            $old_man->setShopkeeper('old_man');
            $old_man->addInventory(0, (in_array($world->config('mode.weapons'), ['swordless', 'vanilla'])) ? Item::get('ThreeHundredRupees', $world)
                : Item::get('ProgressiveSword', $world), 0);
        }

        $shops->filter(function ($shop) use ($world) {
            return !$shop instanceof Shop\TakeAny
                && !$shop instanceof Shop\Upgrade
                && (!$world instanceof \ALttP\World\Inverted || $shop->getName() != "Dark World Lake Hylia Shop");
        })->randomCollection(5)->each(function ($shop) use ($world) {
            $shop->setActive(true);
            if ($world->config('rom.rupeeBow', false)) {
                $shop->addInventory(0, Item::get('ShopArrow', $world), 80);
            }
            if ($world->config('rom.genericKeys', false)) {
                $shop->addInventory(1, Item::get('ShopKey', $world), 100);
            }
            $shop->addInventory(2, Item::get('TenBombs', $world), 50);
        });

        if ($world->config('rom.rupeeBow', false)) {
            // One shop has arrows for sale, we need to set the price correct for
            $dw_shop = $world->getShop("Dark World Forest Shop");
            $dw_shop->setActive(true);
            foreach ($dw_shop->getInventory() as $slot => $data) {
                if ($data['item'] instanceof Item\Arrow) {
                    $dw_shop->addInventory((int) $slot, Item::get('ShopArrow', $world), 80);
                }
            }

            switch ($world->config('rom.HardMode', 0)) {
                case 1:
                case 2:
                case 3:
                    $world->getShop("Capacity Upgrade")->clearInventory();

                    break;
                default:
                    $world->getShop("Capacity Upgrade")->clearInventory()
                        ->addInventory(0, Item::get('BombUpgrade5', $world), 100, 7);
            }
        }
    }

    /**
     * Randomize portions of the ending credits sequence
     *
     * @param \ALttP\World  $world  world to randomize credits for
     *
     * @return $this
     */
    public function randomizeCredits(World $world)
    {
        $world->setCredit('castle', Arr::first(fy_shuffle([
            "the return of the king",
            "fellowship of the ring",
            "the two towers",
        ])));

        $world->setCredit('sanctuary', Arr::first(fy_shuffle([
            "the loyal priest",
            "read a book",
            "sits in own pew",
            "heal plz",
        ])));

        $name = Arr::first(fy_shuffle([
            "sahasralah", "sabotaging", "sacahuista", "sacahuiste", "saccharase", "saccharide", "saccharify",
            "saccharine", "saccharins", "sacerdotal", "sackcloths", "salmonella", "saltarelli", "saltarello",
            "saltations", "saltbushes", "saltcellar", "saltshaker", "salubrious", "sandgrouse", "sandlotter",
            "sandstorms", "sandwiched", "sauerkraut", "schipperke", "schismatic", "schizocarp", "schmalzier",
            "schmeering", "schmoosing", "shibboleth", "shovelnose", "sahananana", "sarararara", "salamander",
            "sharshalah", "shahabadoo", "sassafrass", "saddlebags", "sandalwood", "shagadelic", "sandcastle",
            "saltpeters", "shabbiness", "shlrshlrsh", "sassyralph", "sallyacorn", "sahasrahbot"
        ]));
        $world->setCredit('kakariko', "$name's homecoming");

        $world->setCredit('lumberjacks', Arr::first(fy_shuffle([
            "twin lumberjacks",
            "fresh flapjacks",
            "two woodchoppers",
            "double lumberman",
            "lumberclones",
            "woodfellas",
            "dos axes",
        ])));

        switch (get_random_int(0, 1)) {
            case 1:
                $world->setCredit('smithy', "the dwarven breadsmiths");
                break;
        }

        $world->setCredit('bridge', Arr::first(fy_shuffle([
            "the lost old man",
            "gary the old man",
            "Your ad here",
        ])));

        $world->setCredit('woods', Arr::first(fy_shuffle([
            "the forest thief",
            "dancing pickles",
            "flying vultures",
        ])));

        $world->setCredit('well', Arr::first(fy_shuffle([
            "venus. queen of faeries",
            "Venus was her name",
            "I'm your Venus",
            "Yeah, baby, she's got it",
            "Venus, I'm your fire",
            "Venus, At your desire",
            "Venus Love Chain",
            "Venus Crescent Beam",
        ])));

        return $this;
    }

    function getTextArray(string $file)
    {
        return array_filter(explode(
            "\n-\n",
            (string) preg_replace(
                '/^-\n/',
                '',
                (string) preg_replace('/\r\n/', "\n", (string) file_get_contents(base_path($file)))
            )
        ));
    }

    /**
     * Set all texts for this randomization
     *
     * @param \ALttP\World  $world  world to randomize text for
     *
     * @return $this
     */
    public function setTexts(World $world)
    {
        $strings = cache()->rememberForever('strings', function () {
            return [
                'uncle' => $this->getTextArray('strings/uncle.txt'),
                'tavern_man' => $this->getTextArray('strings/tavern_man.txt'),
                'blind' => $this->getTextArray('strings/blind.txt'),
                'ganon_1' => $this->getTextArray('strings/ganon_1.txt'),
                'triforce' => $this->getTextArray('strings/triforce.txt'),
            ];
        });

        $boots_location = $world->getLocationsWithItem(Item::get('PegasusBoots', $world))->first();

        if ($world->config('spoil.BootsLocation', false) && $boots_location) {
            Log::info('Boots revealed');
            switch ($boots_location->getName()) {
                case "Link's House":
                    $world->setText('uncle_leaving_text', "Lonk!\nYou'll never\nfind the boots");
                    break;
                case "Maze Race":
                    $world->setText('uncle_leaving_text', "Boots at race?\nSeed confirmed\nimpossible.");
                    break;
                default:
                    $world->setText('uncle_leaving_text', "Lonk! Boots\nare in the\n" . $boots_location->getRegion()->getName());
            }
        } else {
            $world->setText('uncle_leaving_text', Arr::first(fy_shuffle($strings['uncle'])));
        }

        $green_pendant_location = $world->getLocationsWithItem(Item::get('PendantOfCourage', $world))->first();

        $world->setText('sahasrahla_bring_courage', "Want something\nfor free? Go\nearn the green\npendant in\n"
            . $green_pendant_location->getRegion()->getName()
            . "\nand I'll give\nyou something.");

        $crystal5_location = $world->getLocationsWithItem(Item::get('Crystal5', $world))->first();
        $crystal6_location = $world->getLocationsWithItem(Item::get('Crystal6', $world))->first();

        $world->setText('bomb_shop', "bring me the\ncrystals from\n"
            . $crystal5_location->getRegion()->getName()
            . "\nand\n"
            . $crystal6_location->getRegion()->getName()
            . "\nso I can make\na big bomb!");

        $world->setText('blind_by_the_light', Arr::first(fy_shuffle($strings['blind'])));

        $world->setText('kakariko_tavern_fisherman', Arr::first(fy_shuffle($strings['tavern_man'])));

        $world->setText('ganon_fall_in', Arr::first(fy_shuffle($strings['ganon_1'])));

        $world->setText('ganon_phase_3_alt', "Got wax in\nyour ears?\nI cannot die!");

        $silver_arrows_location = $world->getLocationsWithItem(Item::get('SilverArrowUpgrade', $world))->first();
        if (!$silver_arrows_location) {
            $silver_arrows_location = $world->getLocationsWithItem(Item::get('BowAndSilverArrows', $world))->first();
        }

        if (!$silver_arrows_location) {
            $world->setText('ganon_phase_3_no_silvers', "Did you find\nthe arrows on\nPlanet Zebes?");
        } else {
            switch ($silver_arrows_location->getRegion()->getName()) {
                case "Ganons Tower":
                    $world->setText('ganon_phase_3_no_silvers', "Did you find\nthe arrows in\nMy tower?");
                    break;
                default:
                    $world->setText('ganon_phase_3_no_silvers', "Did you find\nthe arrows in\n" . $silver_arrows_location->getRegion()->getName());
            }
        }

        // progressive bow hint and handling
        // @todo this swap of item really shouldn't happen here, we don't know
        // for sure that the items haven't already been written to the rom.
        $progressive_bow_locations = $world->getLocationsWithItem(Item::get('ProgressiveBow', $world))->randomCollection(2);
        if ($progressive_bow_locations->count() > 0) {
            $first_location = $progressive_bow_locations->pop();
            switch ($first_location->getRegion()->getName()) {
                case "Ganons Tower":
                    $world->setText('ganon_phase_3_no_silvers', "Did you find\nthe arrows in\nMy tower?");
                    break;
                default:
                    $world->setText('ganon_phase_3_no_silvers', "Did you find\nthe arrows in\n" . $first_location->getRegion()->getName());
            }
            // Progressive Bow Alternate
            $first_location->setItem(new Item\Bow('ProgressiveBow', [0x65], $world));

            if ($progressive_bow_locations->count() > 0) {
                $second_location = $progressive_bow_locations->pop();
                switch ($second_location->getRegion()->getName()) {
                    case "Ganons Tower":
                        $world->setText('ganon_phase_3_no_silvers_alt', "Did you find\nthe arrows in\nMy tower?");
                        break;
                    default:
                        $world->setText('ganon_phase_3_no_silvers_alt', "Did you find\nthe arrows in\n" . $second_location->getRegion()->getName());
                }
            }
            // Remove Hint in Hard+ Item Pool
            if ($world->config('item.overflow.count.Bow') < 2) {
                $world->setText('ganon_phase_3_no_silvers', "Did you find\nthe arrows on\nPlanet Zebes?");
                $world->setText('ganon_phase_3_no_silvers_alt', "Did you find\nthe arrows on\nPlanet Zebes?");
                // Special No Silvers "Hint" for Crowd Control
                if ($world->config('item.pool') == 'crowd_control') {
                    $world->setText('ganon_phase_3_no_silvers', "Chat said no\nto Silvers.\nIt's over Hero");
                    $world->setText('ganon_phase_3_no_silvers_alt', "Chat said no\nto Silvers.\nIt's over Hero");
                }
            }
        }

        if ($world->config('crystals.tower') < 7) {
            $tower_string = $world->config('crystals.tower') == 1 ? 'You need %d crystal to enter.' : 'You need %d crystals to enter.';
            $tower_require = sprintf($tower_string, $world->config('crystals.tower'));
            $world->setText('sign_ganons_tower', $tower_require);
        }
        if ($world->config('crystals.ganon') < 7) {
            $ganon_string = $world->config('crystals.ganon') == 1 ? 'You need %d crystal to beat Ganon.' : 'You need %d crystals to beat Ganon.';
            $ganon_require = sprintf($ganon_string, $world->config('crystals.ganon'));
            $world->setText('sign_ganon', $ganon_require);
        }

        switch ($world->config('goal')) {
            case 'pedestal':
                $world->setText('ganon_fall_in_alt', "You cannot\nkill me. You\nshould go for\nyour real goal\nIt's on the\npedestal.\n\nYou dingus!\n");
                $world->setText('sign_ganon', "You need to get to the pedestal... Dingus!");

                break;
            case 'triforce-hunt':
                $world->setText('ganon_fall_in_alt', "So you thought\nyou could come\nhere and beat\nme? I have\nhidden the\nTriforce\npieces well.\nWithout them,\nyou can't win!");
                $world->setText('sign_ganon', "Go find the Triforce pieces... Dingus!");
                $world->setText('murahdahla', sprintf("Hello @. I\nam Murahdahla, brother of\nSahasrahla and Aginah. Behold the power of\ninvisibility.\n\n\n\n… … …\n\nWait! you can see me? I knew I should have\nhidden in  a hollow tree. If you bring\n%d triforce pieces, I can reassemble it.", $world->config('item.Goal.Required')));

                break;
            case 'dungeons':
                $world->setText('sign_ganon', "You need to defeat all of Ganon's bosses.");

                // no-break
            default:
                $world->setText('ganon_fall_in_alt', "You think you\nare ready to\nface me?\n\nI will not die\n\nunless you\ncomplete your\ngoals. Dingus!");
        }

        $world->setText('end_triforce', "{NOBORDER}\n" . Arr::first(fy_shuffle($strings['triforce'])));

        return $this;
    }

    /**
     * shuffle the prize pack drops.
     *
     * @param \ALttP\World  $world  world to shuffle prizes in
     *
     * @return void
     */
    public function shufflePrizePacks(World $world): void
    {
        if (!$world->config('customPrizePacks', false)) {
            $random_vanilla_packs = fy_shuffle([
                ['Heart', 'Heart', 'Heart', 'Heart', 'RupeeGreen', 'Heart', 'Heart', 'RupeeGreen'],
                ['RupeeBlue', 'RupeeGreen', 'RupeeBlue', 'RupeeRed', 'RupeeBlue', 'RupeeGreen', 'RupeeBlue', 'RupeeBlue'],
                ['MagicRefillFull', 'MagicRefillSmall', 'MagicRefillSmall', 'RupeeBlue', 'MagicRefillFull', 'MagicRefillSmall', 'Heart', 'MagicRefillSmall'],
                ['BombRefill1', 'BombRefill1', 'BombRefill1', 'BombRefill4', 'BombRefill1', 'BombRefill1', 'BombRefill8', 'BombRefill1'],
                ['ArrowRefill5', 'Heart', 'ArrowRefill5', 'ArrowRefill10', 'ArrowRefill5', 'Heart', 'ArrowRefill5', 'ArrowRefill10'],
                ['MagicRefillSmall', 'RupeeGreen', 'Heart', 'ArrowRefill5', 'MagicRefillSmall', 'BombRefill1', 'RupeeGreen', 'Heart'],
                ['Heart', 'Fairy', 'MagicRefillFull', 'RupeeRed', 'BombRefill8', 'Heart', 'RupeeRed', 'ArrowRefill10'],
            ]);

            foreach ($world->getPrizePacks() as $key => $pack) {
                if (!in_array($key, ['0', '1', '2', '3', '4', '5', '6'])) {
                    continue;
                }

                for ($i = 0; $i < 8; $i++) {
                    $drop = Sprite::get($random_vanilla_packs[$key][$i]);

                    if ($drop instanceof \ALttP\Sprite\Droppable) {
                        $world->setDrop($key, $i, $drop);
                    }
                }
            }
        }
    }

    /**
     * Get all the worlds being randomized.
     *
     * @return array
     */
    public function getWorlds(): array
    {
        return $this->worlds;
    }
}
