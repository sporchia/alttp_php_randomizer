<?php

namespace ALttP\World;

use ALttP\Region;
use ALttP\World;

class Inverted extends World
{
    /**
     * Create a new world and initialize all of the Regions within it
     *
     * @param int    $id      Id of this world
     * @param array  $config  config for this world
     *
     * @return void
     */
    public function __construct(int $id = 0, array $config = [])
    {
        $this->config = array_merge([
            'difficulty' => 'normal',
            'logic' => 'NoGlitches',
            'goal' => 'ganon',
        ], $config);

        $this->id = $id;

        $this->regions = [
            'North East Light World' => new Region\Inverted\LightWorld\NorthEast($this),
            'North West Light World' => new Region\Inverted\LightWorld\NorthWest($this),
            'South Light World' => new Region\Inverted\LightWorld\South($this),
            'Escape' => new Region\Inverted\HyruleCastleEscape($this),
            'Eastern Palace' => new Region\Inverted\EasternPalace($this),
            'Desert Palace' => new Region\Inverted\DesertPalace($this),
            'West Death Mountain' => new Region\Inverted\LightWorld\DeathMountain\West($this),
            'East Death Mountain' => new Region\Inverted\LightWorld\DeathMountain\East($this),
            'Tower of Hera' => new Region\Inverted\TowerOfHera($this),
            'Hyrule Castle Tower' => new Region\Inverted\HyruleCastleTower($this),
            'East Dark World Death Mountain' => new Region\Inverted\DarkWorld\DeathMountain\East($this),
            'West Dark World Death Mountain' => new Region\Inverted\DarkWorld\DeathMountain\West($this),
            'North East Dark World' => new Region\Inverted\DarkWorld\NorthEast($this),
            'North West Dark World' => new Region\Inverted\DarkWorld\NorthWest($this),
            'South Dark World' => new Region\Inverted\DarkWorld\South($this),
            'Mire' => new Region\Inverted\DarkWorld\Mire($this),
            'Palace of Darkness' => new Region\Inverted\PalaceOfDarkness($this),
            'Swamp Palace' => new Region\Inverted\SwampPalace($this),
            'Skull Woods' => new Region\Inverted\SkullWoods($this),
            'Thieves Town' => new Region\Inverted\ThievesTown($this),
            'Ice Palace' => new Region\Inverted\IcePalace($this),
            'Misery Mire' => new Region\Inverted\MiseryMire($this),
            'Turtle Rock' => new Region\Inverted\TurtleRock($this),
            'Ganons Tower' => new Region\Inverted\GanonsTower($this),
            'Medallions' => new Region\Standard\Medallions($this),
            'Fountains' => new Region\Standard\Fountains($this),
        ];

        parent::__construct($id, $config);
    }
}
