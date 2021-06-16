<?php
return [
    'title' => 'Randomiseur',
    'preset' => [
        'title' => 'Choisissez vos Paramètres',
        'customize' => 'Customisation',
        'options' => [
            'default' => 'Par défaut',
            'beginner' => 'Débutant',
            'veetorp' => 'OWG (La préférée de Veetorp!)',
            'crosskeys' => 'Clésordre et Entrées',
            'quick' => 'Super Rapide',
            'nightmare' => 'Cauchemars',
            'tournament' => 'Tournoi',
            'custom' => 'Personnalisé',
        ],
    ],
    'placement' => [
        'title' => 'Placement des objets',
    ],
    'item_placement' => [
        'title' => 'Placement des objets',
        'options' => [
            'basic' => 'Basique',
            'advanced' => 'Avancé',
        ],
    ],
    'dungeon_items' => [
        'title' => 'Objets des Donjons',
        'options' => [
            'standard' => 'Standard',
            'mc' => 'Cartes et Boussoles',
            'mcs' => 'Cartes, Boussoles et Petites Clefs',
            'full' => 'Clésordre',
        ],
    ],
    'accessibility' => [
        'title' => 'Accessibilité',
        'options' => [
            'items' => '100% Inventaire',
            'locations' => '100% Accessible',
            'none' => 'Achevable',
        ],
    ],
    'glitches_required' => [
        'title' => 'Glitchs requis',
        'options' => [
            'none' => 'Aucun',
            'overworld_glitches' => 'Glitchs Monde Extérieur',
            'major_glitches' => 'Glitchs Majeurs',
            'no_logic' => 'Sans Logique',
        ],
        'glitch_warning' => 'Cette logique nécessite la connaissance de glitchs majeurs<sup>**</sup>',
    ],
    'goal' => [
        'title' => 'Objectif',
        'options' => [
            'ganon' => 'Vaincre Ganon',
            'fast_ganon' => 'Rapide Ganon',
            'dungeons' => 'Tous les Donjons',
            'pedestal' => 'Piédestal de la Master Sword',
            'triforce-hunt' => 'Morceaux de Triforce ',
        ],
    ],
    'tower_open' => [
        'title' => 'Ouvrir la Tour',
        'options' => [
            '0' => '0 Cristaux',
            '1' => '1 Cristal',
            '2' => '2 Cristaux',
            '3' => '3 Cristaux',
            '4' => '4 Cristaux',
            '5' => '5 Cristaux',
            '6' => '6 Cristaux',
            '7' => '7 Cristaux',
            'random' => 'Aléatoire'
        ],
    ],
    'ganon_open' => [
        'title' => 'Vulnérabilité de Ganon',
        'options' => [
            '0' => '0 Cristaux',
            '1' => '1 Cristal',
            '2' => '2 Cristaux',
            '3' => '3 Cristaux',
            '4' => '4 Cristaux',
            '5' => '5 Cristaux',
            '6' => '6 Cristaux',
            '7' => '7 Cristaux',
            'random' => 'Aléatoire'
        ],
    ],
    'gameplay' => [
        'title' => 'Gameplay',
    ],
    "world_state" => [
        'title' => 'État du Monde',
        'options' => [
            'standard' => 'Standard',
            'open' => 'Ouvert',
            'inverted' => 'Inversé',
            'retro' => 'Rétro',
        ],
    ],
    "entrance_shuffle" => [
        'title' => 'Mélangeur d’Entrées',
        'options' => [
            'none' => 'Désactivé',
            'simple' => 'Simple',
            'restricted' => 'Restreint',
            'full' => 'Complet',
            'crossed' => 'Croisé',
            'insanity' => 'Insensé',
        ],
    ],
    "boss_shuffle" => [
        'title' => 'Mélangeur de Boss',
        'options' => [
            'none' => 'Désactivé',
            'simple' => 'Simple',
            'full' => 'Complet',
            'random' => 'Aléatoire',
        ],
    ],
    "enemy_shuffle" => [
        'title' => 'Mélangeur d’Ennemis',
        'options' => [
            'none' => 'Désactivé',
            'shuffled' => 'Intervertis',
            'random' => 'Aléatoires',
        ],
    ],
    "hints" => [
        'title' => 'Indices',
        'options' => [
            'on' => 'Activés',
            'off' => 'Désactivés',
        ],
    ],
    'weapons' => [
        'title' => 'Épées',
        'options' => [
            'randomized' => 'Randomisées',
            'assured' => 'Assurée',
            'vanilla' => 'Originales',
            'swordless' => 'Sans Épée',
        ],
    ],
    'item_pool' => [
        'title' => 'Objets disponibles',
        'options' => [
            'easy' => 'Facile',
            'normal' => 'Normal',
            'hard' => 'Difficile',
            'expert' => 'Expert',
            'crowd_control' => 'Crowd Control',
        ],
        'crowd_control_warning' => '<sup>*</sup> Ce paramètre est prévu pour être utilisé avec l’extension Twitch Crowd Control. En savoir plus: <a href="https://crowdcontrol.live/" target="_blank" rel=”noopener noreferrer”>https://crowdcontrol.live/</a>',
    ],
    'item_functionality' => [
        'title' => 'Fonctionnalité des Objets',
        'options' => [
            'easy' => 'Facile',
            'normal' => 'Normal',
            'hard' => 'Difficile',
            'expert' => 'Expert',
        ],
    ],
    'enemy_damage' => [
        'title' => 'Dégâts des Ennemis',
        'options' => [
            'default' => 'Par défaut',
            'shuffled' => 'Intervertis',
            'random' => 'Aléatoire',
        ],
    ],
    'enemy_health' => [
        'title' => 'Vie des Ennemis',
        'options' => [
            'default' => 'Par défaut',
            'easy' => 'Facile',
            'hard' => 'Difficile',
            'expert' => 'Expert',
        ],
    ],
    'spoiler' => [
        'title' => 'Spoilers',
        'options' => [
            'off' => 'Desactivé',
            'on' => 'Activé',
            'generate' => 'Seulement sur Generate',
        ],
    ],
    'generate' => [
        'race' => 'Générer une ROM de Course',
        'race_warning' => 'Les spoilers ne seront <span class="running-now">jamais</span> disponibles pour cette option.',
        'spoiler_race' => 'Générer une ROM avec Spoiler',
        'casual' => 'Générer une ROM',
        'back' => 'Changer les Paramètres',
        'forward' => 'Voir le jeu généré',
        'regenerate' => 'Générer à nouveau',
        'regenerate_tooltip' => 'Générer à nouveau avec les mêmes paramètres',
        'generating' => 'Génération...',
    ],
    'details' => [
        'title' => 'Détails du jeu',
        'save_spoiler' => 'Enregistrer la spoiler',
        'save_rom' => 'Sauvegarder la ROM',
    ],
    // deprecated
    'variation' => [
        'title' => 'Variation',
    ],
    'difficulty' => [
        'title' => 'Difficulté',
        'options' => [
            'easy' => 'Easy',
            'normal' => 'Normal',
            'hard' => 'Difficile',
            'expert' => 'Expert',
            'insane' => 'Insensé',
            'crowdControl' => 'Crowd Control',
        ],
    ],
];
