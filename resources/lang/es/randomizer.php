<?php
return [
    'title' => 'Randomizer',
    'preset' => [
        'title' => 'Seleccionar plantilla',
        'customize' => 'Customizar',
        'options' => [
            'default' => 'Por Defecto',
            'beginner' => 'Principiante',
            'veetorp' => 'Glitches de Superfície (el favorito de Veetorp)',
            'crosskeys' => 'Crosskeys',
            'quick' => 'Súper Rápido',
            'nightmare' => 'Pesadilla',
            'tournament' => 'Torneo',
            'custom' => 'Personalizado',
        ],
    ],
    'placement' => [
        'title' => 'Colocación de Objetos',
    ],
    'item_placement' => [
        'title' => 'Colocación de Objetos',
        'options' => [
            'basic' => 'Básica',
            'advanced' => 'Avanzada',
        ],
    ],
    'dungeon_items' => [
        'title' => 'Objetos de Mazmorras',
        'options' => [
            'standard' => 'Normal',
            'mc' => 'Mapas/Brújulas',
            'mcs' => 'Mapas/Brújulas/Llaves pequeñas',
            'full' => 'Keysanity',
        ],
    ],
    'accessibility' => [
        'title' => 'Accesibilidad',
        'options' => [
            'items' => '100% inventario',
            'locations' => '100% localizaciones',
            'none' => 'Completable',
        ],
    ],
    'glitches_required' => [
        'title' => 'Glitches Requeridos',
        'options' => [
            'none' => 'Ninguno',
            'overworld_glitches' => 'Glitches de la Superfície',
            'major_glitches' => 'Glitches Mayores',
            'no_logic' => 'Sin lógica',
        ],
        'glitch_warning' => 'Esta Lógica require conocimiento de Glithes Mayores<sup>**</sup>',
    ],
    'goal' => [
        'title' => 'Objetivo',
        'options' => [
            'ganon' => 'Derrotar a Ganon',
            'fast_ganon' => 'Ganon Rápido',
            'dungeons' => 'Todas las mazmorras',
            'pedestal' => 'Pedestal de la Espada Maestra',
            'triforce-hunt' => 'Piezas de la Trifuerza',
        ],
    ],
    'tower_open' => [
        'title' => 'Torre Abierta',
        'options' => [
            '0' => '0 Cristales',
            '1' => '1 Cristal',
            '2' => '2 Cristales',
            '3' => '3 Cristales',
            '4' => '4 Cristales',
            '5' => '5 Cristales',
            '6' => '6 Cristales',
            '7' => '7 Cristales',
            'random' => 'Aleatorio'
        ],
    ],
    'ganon_open' => [
        'title' => 'Ganon Vulnerable',
        'options' => [
            '0' => '0 Cristales',
            '1' => '1 Cristal',
            '2' => '2 Cristales',
            '3' => '3 Cristales',
            '4' => '4 Cristales',
            '5' => '5 Cristales',
            '6' => '6 Cristales',
            '7' => '7 Cristales',
            'random' => 'Aleatorio'
        ],
    ],
    'gameplay' => [
        'title' => 'Gameplay',
    ],
    "world_state" => [
        'title' => 'Estado del mundo',
        'options' => [
            'standard' => 'Estándar',
            'open' => 'Abierto',
            'inverted' => 'Inverso',
            'retro' => 'Retro',
        ],
    ],
    "entrance_shuffle" => [
        'title' => 'Randomizer de entradas',
        'options' => [
            'none' => 'Ninguno',
            'simple' => 'Simple',
            'restricted' => 'Restringido',
            'full' => 'Completo',
            'crossed' => 'Cruzado',
            'insanity' => 'Locura',
        ],
    ],
    "boss_shuffle" => [
        'title' => 'Randomizer de Jefes',
        'options' => [
            'none' => 'Ninguno',
            'simple' => 'Simple',
            'full' => 'Completo',
            'random' => 'Aleatorio',
        ],
    ],
    "enemy_shuffle" => [
        'title' => 'Randomizer de Enemigos',
        'options' => [
            'none' => 'Ninguno',
            'shuffled' => 'Barajado',
            'random' => 'Aleatorio',
        ],
    ],
    "hints" => [
        'title' => 'Pistas',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    'weapons' => [
        'title' => 'Espadas',
        'options' => [
            'randomized' => 'Randomizadas',
            'assured' => 'Aseguradas',
            'vanilla' => 'Vanilla',
            'swordless' => 'Sin Espadas',
        ],
    ],
    'item_pool' => [
        'title' => 'Reserva de Objetos',
        'options' => [
            'easy' => 'Fácil',
            'normal' => 'Normal',
            'hard' => 'Difícil',
            'expert' => 'Experto',
            'crowd_control' => 'Crowd Control',
        ],
        'crowd_control_warning' => '<sup>*</sup> Esta opción está hecha para jugarse con la extensión de Twitch de <i>Crowd Control</i>. Para saber más: <a href="https://crowdcontrol.live/" target="_blank" rel=”noopener noreferrer”>https://crowdcontrol.live/</a>',
    ],
    'item_functionality' => [
        'title' => 'Funcionalidad de Objetos',
        'options' => [
            'easy' => 'Fácil',
            'normal' => 'Normal',
            'hard' => 'Difícil',
            'expert' => 'Experto',
        ],
    ],
    'enemy_damage' => [
        'title' => 'Daño de Enemigos',
        'options' => [
            'default' => 'Por Defecto',
            'shuffled' => 'Barajado',
            'random' => 'Aleatorio',
        ],
    ],
    'enemy_health' => [
        'title' => 'Vida de Enemigos',
        'options' => [
            'default' => 'Por Defecto',
            'easy' => 'Fácil',
            'hard' => 'Difícil',
            'expert' => 'Experto',
        ],
    ],
    'spoiler' => [
        'title' => 'Spoilers',
        'options' => [
            'off' => 'Disabled',
            'on' => 'Enabled',
            'generate' => 'Solo en Generar',
        ],
    ],
    'generate' => [
        'race' => 'Generar ROM para carreras',
        'race_warning' => '<span class="running-now">sin spoilers</span>',
        'spoiler_race' => 'Generar ROM para carreras (con spoilers)',
        'casual' => 'Generar ROM',
        'back' => 'Cambiar ajustes',
        'forward' => 'Ver juego generado',
        'regenerate' => 'Generar otra vez',
        'regenerate_tooltip' => 'Generar otra partida con las mismas opciones',
        'generating' => 'Generando...',
    ],
    'details' => [
        'title' => 'Detalles del juego',
        'save_spoiler' => 'Guardar spoiler',
        'save_rom' => 'Guardar ROM',
    ],
    // deprecated
    'variation' => [
        'title' => 'Variación',
    ],
    'difficulty' => [
        'title' => 'Dificultad',
        'options' => [
            'easy' => 'Fácil',
            'normal' => 'Normal',
            'hard' => 'Difícil',
            'expert' => 'Experto',
            'insane' => 'Locura',
            'crowdControl' => 'Crowd Control',
        ],
    ],
];
