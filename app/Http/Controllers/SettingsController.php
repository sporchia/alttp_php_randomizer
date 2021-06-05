<?php

namespace ALttP\Http\Controllers;

use ALttP\Item;
use ALttP\Location;
use ALttP\Rom;
use ALttP\Sprite;
use ALttP\World;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

/**
 * Controller to handle all requests for front end configs. Basically this
 * informs the front end what the back end expects.
 */
class SettingsController extends Controller
{
    /** @var array */
    protected $items = [];
    /** @var array */
    protected $drops = [];

    /**
     * Create a new controller class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->drops = config('item.drop');
        $this->items = [];
        foreach (Arr::only(config('item'), ['advancement', 'nice', 'junk', 'dungeon']) as $group) {
            foreach ($group as $item => $value) {
                if (!($this->items[$item] ?? false)) {
                    $this->items[$item] = 0;
                }
                $this->items[$item] += $value;
            }
        }
    }

    /**
     * Get item randomizer settings.
     *
     * @return array
     */
    public function item(): array
    {
        return config('alttp.randomizer.item');
    }

    /**
     * Get all customizer options (cached).
     *
     * @todo refactor this into smaller functions
     *
     * @return array
     */
    public function customizer(): array
    {
        return Cache::rememberForever('customizer_settings', function () {
            $world = World::factory();
            $items = Item::all($world);
            $sprites = Sprite::all();
            return [
                'locations' => array_values($world->getLocations()->filter(function ($location) {
                    return !$location instanceof Location\Prize\Event
                        && !$location instanceof Location\Trade;
                })->map(function ($location) {
                    return [
                        'hash' => base64_encode($location->getName()),
                        'name' => $location->getName(),
                        'region' => $location->getRegion()->getName(),
                        'class' => $location instanceof Location\Fountain ? 'bottles'
                            : ($location instanceof Location\Medallion ? 'medallions'
                                : ($location instanceof Location\Prize ? 'prizes' : 'items')),
                    ];
                })),
                'prizepacks' => array_values(array_map(function ($pack) {
                    return [
                        'name' => $pack->getName(),
                        'slots' => count($pack->getDrops()),
                    ];
                }, $world->getPrizePacks())),
                'items' => array_merge(
                    [
                        ['value' => 'auto_fill', 'name' => 'item.Random', 'placed' => 0],
                        ['value' => 'BottleWithRandom', 'name' => 'item.BottleWithRandom', 'count' => 4, 'placed' => 0],
                    ],
                    $items->filter(function ($item) use ($world) {
                        return !$item instanceof Item\Pendant
                            && !$item instanceof Item\Crystal
                            && !$item instanceof Item\Event
                            && !$item instanceof Item\Programmable
                            && !$item instanceof Item\BottleContents
                            && !in_array($item->getRawName(), [
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
                                'ProgressiveBowAlternate',
                                'BombUpgrade50',
                                'ArrowUpgrade70',
                            ])
                            || $item == Item::get('Triforce', $world);
                    })->map(function ($item) {
                        return [
                            'value' => $item->getName(),
                            'name' => $item->getI18nName(),
                            'count' => $this->items[$item->getRawName()] ?? 0,
                            'placed' => 0,
                        ];
                    })
                ),
                'prizes' => array_merge(
                    [
                        ['value' => 'auto_fill', 'name' => 'item.Random', 'placed' => 0],
                    ],
                    $items->filter(function ($item) {
                        return $item instanceof Item\Pendant
                            || $item instanceof Item\Crystal;
                    })->map(function ($item) {
                        return [
                            'value' => $item->getName(),
                            'name' => $item->getI18nName(),
                            'count' => 0,
                            'placed' => 0,
                        ];
                    })
                ),
                'medallions' => array_merge(
                    [
                        ['value' => 'auto_fill', 'name' => 'item.Random', 'placed' => 0],
                    ],
                    $items->filter(function ($item) {
                        return $item instanceof Item\Medallion;
                    })->map(function ($item) {
                        return [
                            'value' => $item->getName(),
                            'name' => $item->getI18nName(),
                            'count' => 0,
                            'placed' => 0,
                        ];
                    })
                ),
                'bottles' => array_merge(
                    [
                        ['value' => 'auto_fill', 'name' => 'item.Random', 'placed' => 0],
                    ],
                    $items->filter(function ($item) {
                        return $item instanceof Item\Bottle;
                    })->map(function ($item) {
                        return [
                            'value' => $item->getName(),
                            'name' => $item->getI18nName(),
                            'count' => 0,
                            'placed' => 0,
                        ];
                    })
                ),
                'droppables' => array_merge([
                    ['value' => 'auto_fill', 'name' => 'item.Random', 'placed' => 0],
                ], array_values(
                    $sprites->filter(function ($sprite) {
                        return $sprite instanceof Sprite\Droppable;
                    })->map(function ($item) {
                        return [
                            'value' => $item->getName(),
                            'name' => $item->getI18nName(),
                            'count' => $this->drops[$item->getName()] ?? 0,
                            'placed' => 0,
                        ];
                    })
                )),
            ];
        });
    }

    /**
     * Get information on the current ROM patch.
     *
     * @return array
     */
    public function rom(): array
    {
        return [
            'rom_hash' => Rom::HASH,
            'base_file' => sprintf('/bps/%s.bps', Rom::HASH),
        ];
    }

    /**
     * Get all current Link sprite options.
     *
     * @return array
     */
    public function sprites(): array
    {
        return collect(config('sprites'))->map(function ($info, $file) {
            if ($file === '001.link.1.zspr') {
                return [
                    'name' => $info['name'],
                    'author' => $info['author'],
                    'version' => $info['version'],
                    'file' => null,
                    'preview' => null,
                    'tags' => $info['tags'] ?? [],
                    'usage' => $info['usage'] ?? []
                ];
            }
            return [
                'name' => $info['name'],
                'author' => $info['author'],
                'version' => $info['version'],
                'file' => 'https://alttpr.s3.us-east-2.amazonaws.com/' . $file,
                'preview' => 'https://alttpr.s3.us-east-2.amazonaws.com/' . $file . '.png',
                'tags' => $info['tags'] ?? [],
                'usage' => $info['usage'] ?? []
            ];
        })->values()->all();
    }
}
