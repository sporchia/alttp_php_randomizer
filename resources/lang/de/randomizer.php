<?php
return [
    'title' => 'Randomizer',
    'preset' => [
        'title' => 'Wähle Voreinstellungen',
        'customize' => 'Customire',
        'options' => [
            'default' => 'Default',
            'beginner' => 'Beginner',
            'veetorp' => 'OWG (Veetorp’s Favorit)',
            'crosskeys' => 'Crosskeys',
            'quick' => 'Super Schnell',
            'nightmare' => 'Albtraum',
            'tournament' => 'Turnier',
            'custom' => 'Benutzerdefiniert',
        ],
    ],
    'placement' => [
        'title' => 'Gegenstandsplatzierung',
    ],
    'item_placement' => [
        'title' => 'Gegenstandsplatzierung',
        'options' => [
            'basic' => 'Basis',
            'advanced' => 'Erweitert',
        ],
    ],
    'dungeon_items' => [
        'title' => 'Palastgegenstände',
        'options' => [
            'standard' => 'Standard',
            'mc' => 'Karten/Kompässe',
            'mcs' => 'Karten/Kompässe/kleine Schlüssel',
            'full' => 'Keysanity',
        ],
    ],
    'accessibility' => [
        'title' => 'Zugänglichkeit',
        'options' => [
            'items' => '100% Inventar',
            'locations' => '100% der Orte',
            'none' => 'Schaffbar',
        ],
    ],
    'glitches_required' => [
        'title' => 'Vorausgesetzte Glitches',
        'options' => [
            'none' => 'Keine',
            'overworld_glitches' => 'Overworld Glitches',
            'major_glitches' => 'Major Glitches',
            'no_logic' => 'Keine Logik',
        ],
        'glitch_warning' => 'Diese Einstellung setzt Wissen von Major Glitches voraus<sup>**</sup>',
    ],
    'goal' => [
        'title' => 'Ziel',
        'options' => [
            'ganon' => 'Besiege Ganon',
            'fast_ganon' => 'Fast Ganon',
            'dungeons' => 'Alle Dungeons',
            'pedestal' => 'Master-Schwert Sockel',
            'triforce-hunt' => 'Triforce-Splitter',
        ],
    ],
    'tower_open' => [
        'title' => 'Turm öffnen',
        'options' => [
            '0' => '0 Kristalle',
            '1' => '1 Kristall',
            '2' => '2 Kristalle',
            '3' => '3 Kristalle',
            '4' => '4 Kristalle',
            '5' => '5 Kristalle',
            '6' => '6 Kristalle',
            '7' => '7 Kristalle',
            'random' => 'Zufällig'
        ],
    ],
    'ganon_open' => [
        'title' => 'Ganon verwundbar machen',
        'options' => [
            '0' => '0 Kristalle',
            '1' => '1 Kristall',
            '2' => '2 Kristalle',
            '3' => '3 Kristalle',
            '4' => '4 Kristalle',
            '5' => '5 Kristalle',
            '6' => '6 Kristalle',
            '7' => '7 Kristalle',
            'random' => 'Zufällig'
        ],
    ],
    'gameplay' => [
        'title' => 'Gameplay',
    ],
    "world_state" => [
        'title' => 'Zustand der Welt',
        'options' => [
            'standard' => 'Standard',
            'open' => 'Offen',
            'inverted' => 'Invertiert',
            'retro' => 'Retro',
        ],
    ],
    "entrance_shuffle" => [
        'title' => 'Entrance Shuffle',
        'options' => [
            'none' => 'Keine',
            'simple' => 'Simpel',
            'restricted' => 'Beschränkt',
            'full' => 'Voll',
            'crossed' => 'Gekreuzt',
            'insanity' => 'Wahnsinn',
        ],
    ],
    "boss_shuffle" => [
        'title' => 'Boss Shuffle',
        'options' => [
            'none' => 'Keine',
            'simple' => 'Simpel',
            'full' => 'Voll',
            'random' => 'Zufällig',
        ],
    ],
    "enemy_shuffle" => [
        'title' => 'Enemy Shuffle',
        'options' => [
            'none' => 'Keine',
            'shuffled' => 'Gemischt',
            'random' => 'Zufällig',
        ],
    ],
    "hints" => [
        'title' => 'Hinweise',
        'options' => [
            'on' => 'An',
            'off' => 'Aus',
        ],
    ],
    'weapons' => [
        'title' => 'Schwerter',
        'options' => [
            'randomized' => 'Zufällig',
            'assured' => 'Garantiert',
            'vanilla' => 'Vanilla',
            'swordless' => 'Schwertlos',
        ],
    ],
    'item_pool' => [
        'title' => 'Item Pool',
        'options' => [
            'easy' => 'Einfach',
            'normal' => 'Normal',
            'hard' => 'Schwer',
            'expert' => 'Experte',
            'crowd_control' => 'Crowd Control',
        ],
        'crowd_control_warning' => '<sup>*</sup> Diese Erweiterung ist für die Verwundung mit Twitches Crowd Control Erweiterung gedacht. Für mehr Info´s: <a href="https://crowdcontrol.live/" target="_blank" rel=”noopener noreferrer”>https://crowdcontrol.live/</a>',
    ],
    'item_functionality' => [
        'title' => 'Gegendstands Funktionalität',
        'options' => [
            'easy' => 'Einfach',
            'normal' => 'Normal',
            'hard' => 'Schwer',
            'expert' => 'Experte',
        ],
    ],
    'enemy_damage' => [
        'title' => 'Gegnerschaden',
        'options' => [
            'default' => 'Default',
            'shuffled' => 'Gemischt',
            'random' => 'Zufällig',
        ],
    ],
    'enemy_health' => [
        'title' => 'Gegnerleben',
        'options' => [
            'default' => 'Default',
            'easy' => 'Einfach',
            'hard' => 'Schwer',
            'expert' => 'Experte',
        ],
    ],
    'spoiler' => [
        'title' => 'Spoilers',
        'options' => [
            'off' => 'Disabled',
            'on' => 'Enabled',
            'generate' => 'Nur bei Generieren',
        ],
    ],
    'generate' => [
        'race' => 'Generiere ROM für Rennen',
        'race_warning' => '<span class="running-now">Spoilerfrei</span>',
        'spoiler_race' => 'ROM für Spoiler Rennen',
        'casual' => 'Generiere ROM',
        'back' => 'Ändere Einstellung',
        'forward' => 'Generiertes Spiel anzeigen',
        'regenerate' => 'Erneut erstellen',
        'regenerate_tooltip' => 'Erstellt ein neues Spiel mit den gleichen Einstellungen',
        'generating' => 'Am erstellen...',
    ],
    'details' => [
        'title' => 'Spiel Details',
        'save_spoiler' => 'Speichere Spoiler',
        'save_rom' => 'Speichere ROM',
    ],
    // deprecated
    'variation' => [
        'title' => 'Variation',
    ],
    'difficulty' => [
        'title' => 'Schwierigkeitsgrad',
        'options' => [
            'easy' => 'Einfach',
            'normal' => 'Normal',
            'hard' => 'Schwer',
            'expert' => 'Experte',
            'insane' => 'Wahnsinnig',
            'crowdControl' => 'Crowd Control',
        ],
    ],
];
