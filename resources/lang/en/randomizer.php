<?php
return [
    'title' => 'Randomizer',
    'preset' => [
        'title' => 'Select Preset',
        'customize' => 'Customize',
        'options' => [
            'default' => 'Default',
            'beginner' => 'Beginner',
            'veetorp' => 'OWG (Veetorp’s Favorite)',
            'crosskeys' => 'Crosskeys',
            'quick' => 'Super Quick',
            'nightmare' => 'Nightmare',
            'tournament' => 'Tournament',
            'custom' => 'Custom',
        ],
    ],
    'placement' => [
        'title' => 'Item Placement',
    ],
    'item_placement' => [
        'title' => 'Item Placement',
        'options' => [
            'basic' => 'Basic',
            'advanced' => 'Advanced',
        ],
    ],
    'dungeon_items' => [
        'title' => 'Dungeon Item Shuffle',
        'options' => [
            'standard' => 'Standard',
            'mc' => 'Maps/Compasses',
            'mcs' => 'Maps/Compasses/Small Keys',
            'full' => 'Keysanity',
        ],
    ],
    'accessibility' => [
        'title' => 'Accessibility',
        'options' => [
            'items' => '100% Inventory',
            'locations' => '100% Locations',
            'none' => 'Beatable',
        ],
    ],
    'glitches_required' => [
        'title' => 'Glitches Required',
        'options' => [
            'none' => 'None',
            'overworld_glitches' => 'Overworld Glitches',
            'major_glitches' => 'Major Glitches',
            'no_logic' => 'No Logic',
        ],
        'glitch_warning' => 'These settings require knowledge of Major Glitches<sup>**</sup>',
    ],
    'goal' => [
        'title' => 'Goal',
        'options' => [
            'ganon' => 'Defeat Ganon',
            'fast_ganon' => 'Fast Ganon',
            'dungeons' => 'All Dungeons',
            'pedestal' => 'Master Sword Pedestal',
            'triforce-hunt' => 'Triforce Pieces',
        ],
    ],
    'tower_open' => [
        'title' => 'Open Tower',
        'options' => [
            '0' => '0 Crystals',
            '1' => '1 Crystal',
            '2' => '2 Crystals',
            '3' => '3 Crystals',
            '4' => '4 Crystals',
            '5' => '5 Crystals',
            '6' => '6 Crystals',
            '7' => '7 Crystals',
            'random' => 'Random'
        ],
    ],
    'ganon_open' => [
        'title' => 'Ganon Vulnerable',
        'options' => [
            '0' => '0 Crystals',
            '1' => '1 Crystal',
            '2' => '2 Crystals',
            '3' => '3 Crystals',
            '4' => '4 Crystals',
            '5' => '5 Crystals',
            '6' => '6 Crystals',
            '7' => '7 Crystals',
            'random' => 'Random'
        ],
    ],
    'gameplay' => [
        'title' => 'Gameplay',
    ],
    "world_state" => [
        'title' => 'World State',
        'options' => [
            'standard' => 'Standard',
            'open' => 'Open',
            'inverted' => 'Inverted',
            'retro' => 'Retro',
        ],
    ],
    "entrance_shuffle" => [
        'title' => 'Entrance Shuffle',
        'options' => [
            'none' => 'None',
            'simple' => 'Simple',
            'restricted' => 'Restricted',
            'full' => 'Full',
            'crossed' => 'Crossed',
            'insanity' => 'Insanity',
        ],
    ],
    "boss_shuffle" => [
        'title' => 'Boss Shuffle',
        'options' => [
            'none' => 'None',
            'simple' => 'Simple',
            'full' => 'Full',
            'random' => 'Random',
        ],
    ],
    "enemy_shuffle" => [
        'title' => 'Enemy Shuffle',
        'options' => [
            'none' => 'None',
            'shuffled' => 'Shuffled',
            'random' => 'Random',
        ],
    ],
    "hints" => [
        'title' => 'Hints',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    'weapons' => [
        'title' => 'Swords',
        'options' => [
            'randomized' => 'Randomized',
            'assured' => 'Assured',
            'vanilla' => 'Vanilla',
            'swordless' => 'Swordless',
        ],
    ],
    'item_pool' => [
        'title' => 'Item Pool',
        'options' => [
            'easy' => 'Easy',
            'normal' => 'Normal',
            'hard' => 'Hard',
            'expert' => 'Expert',
            'crowd_control' => 'Crowd Control',
        ],
        'crowd_control_warning' => '<sup>*</sup> This setting is meant to be used with the Crowd Control Twitch extension. find out more: <a href="https://crowdcontrol.live/" target="_blank" rel=”noopener noreferrer”>https://crowdcontrol.live/</a>',
    ],
    'item_functionality' => [
        'title' => 'Item Functionality',
        'options' => [
            'easy' => 'Easy',
            'normal' => 'Normal',
            'hard' => 'Hard',
            'expert' => 'Expert',
        ],
    ],
    'enemy_damage' => [
        'title' => 'Enemy Damage',
        'options' => [
            'default' => 'Default',
            'shuffled' => 'Shuffled',
            'random' => 'Random',
        ],
    ],
    'enemy_health' => [
        'title' => 'Enemy Health',
        'options' => [
            'default' => 'Default',
            'easy' => 'Easy',
            'hard' => 'Hard',
            'expert' => 'Expert',
        ],
    ],
    'spoiler' => [
        'title' => 'Spoilers',
        'options' => [
            'off' => 'Disabled',
            'on' => 'Enabled',
            'generate' => 'Only on Generate',
            'mystery' => 'Mystery (settings hidden)'
        ],
    ],
    'generate' => [
        'race' => 'Generate Race ROM',
        'race_warning' => 'Spoilers will <span class="running-now">never</span> be available for this option.',
        'spoiler_race' => 'Generate Normal ROM',
        'casual' => 'Generate ROM',
        'back' => 'Change Settings',
        'forward' => 'View Generated Game',
        'regenerate' => 'Generate Again',
        'regenerate_tooltip' => 'Generate new game with same settings',
        'generating' => 'Generating...',
    ],
    'details' => [
        'title' => 'Game Details',
        'save_spoiler' => 'Save Spoiler',
        'save_rom' => 'Save ROM',
    ],
    // deprecated
    'variation' => [
        'title' => 'Variation',
    ],
    'difficulty' => [
        'title' => 'Difficulty',
        'options' => [
            'easy' => 'Easy',
            'normal' => 'Normal',
            'hard' => 'Hard',
            'expert' => 'Expert',
            'insane' => 'Insane',
            'crowdControl' => 'Crowd Control',
        ],
    ],
];
