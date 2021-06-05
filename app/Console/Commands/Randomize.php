<?php

namespace ALttP\Console\Commands;

use ALttP\Boss;
use ALttP\Item;
use ALttP\Randomizer;
use ALttP\Rom;
use ALttP\Support\Zspr;
use ALttP\World;
use Hashids\Hashids;
use Illuminate\Console\Command;

/**
 * Run randomizer as command.
 */
class Randomize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alttp:randomize {input_file : base ROM to randomize}'
        . ' {output_directory : where to place randomized ROM}'
        . ' {--unrandomized : do not apply randomization to the ROM}'
        . ' {--spoiler : generate a spoiler file}'
        . ' {--heartbeep=half : set heart beep speed}'
        . ' {--heartcolor=red : set heart color}'
        . ' {--skip-md5 : do not validate md5 of base ROM}'
        . ' {--tournament : enable tournament mode}'
        . ' {--bulk=1 : generate multiple ROMs}'
        . ' {--sprite= : sprite file to change links graphics [zspr format]}'
        . ' {--no-rom : do not generate output ROM}'
        . ' {--no-music : mute all music}'
        . ' {--menu-speed=normal : menu speed}'
        . ' {--goal=ganon : set game goal}'
        . ' {--state=standard : set game state}'
        . ' {--weapons=randomized : set weapons mode}'
        . ' {--glitches=none : set glitches}'
        . ' {--crystals_ganon=7 : set ganon crystal requirement}'
        . ' {--crystals_tower=7 : set ganon tower crystal requirement}'
        . ' {--item_placement=basic : set item placement rules}'
        . ' {--dungeon_items=standard : set dungeon item placement}'
        . ' {--accessibility=item : set item/location accessibility}'
        . ' {--hints=on : set hints on or off}'
        . ' {--item_pool=normal : set item pool}'
        . ' {--item_functionality=normal : set item functionality}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a randomized ROM.';

    /** @var array */
    protected $reset_patch;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        $hasher = new Hashids('local', 15);

        if (
            !is_string($this->option('glitches'))
            || !is_string($this->option('goal'))
            || !is_string($this->option('state'))
            || !is_string($this->option('weapons'))
            || !is_string($this->option('menu-speed'))
        ) {
            $this->error('option not string');

            return 101;
        }

        $filename = vsprintf('%s/alttpr_%s_%s_%s_%%s.%%s', [
            $this->argument('output_directory'),
            $this->option('glitches'),
            $this->option('state'),
            $this->option('goal'),
        ]);

        if (!is_string($this->argument('input_file')) || !is_readable($this->argument('input_file'))) {
            $this->error('Source File not readable');
            return 1;
        }

        if (
            !is_string($this->argument('output_directory'))
            || !is_dir($this->argument('output_directory'))
            || !is_writable($this->argument('output_directory'))
        ) {
            $this->error('Target Directory not writable');
            return 2;
        }

        if (is_array($this->option('bulk'))) {
            $this->error('`bulk` cannot be an array');

            return 101;
        }

        $bulk = (int) ($this->option('bulk') ?? 1);

        for ($i = 0; $i < $bulk; $i++) {
            Item::clearCache();
            Boss::clearCache();
            $rom = new Rom($this->argument('input_file'));
            $hash = $hasher->encode((int) (microtime(true) * 1000));

            if (!$this->option('skip-md5') && !$rom->checkMD5()) {
                $rom->resize();

                $rom->applyPatch($this->resetPatch());
            }

            if (!$this->option('skip-md5') && !$rom->checkMD5()) {
                $this->error('MD5 check failed :(');
                return 3;
            }

            if (is_string($this->option('heartcolor'))) {
                $heartColorToUse = $this->option('heartcolor');
                if ($heartColorToUse === 'random') {
                  $colorOptions = ['blue', 'green', 'yellow', 'red'];
                  $heartColorToUse = $colorOptions[get_random_int(0, 3)];
                }
                $rom->setHeartColors($heartColorToUse);
            }

            if (is_string($this->option('heartbeep'))) {
                $rom->setHeartBeepSpeed($this->option('heartbeep'));
            }

            // break out for unrandomized base game
            if ($this->option('unrandomized')) {
                $output_file = sprintf('%s/alttp-%s.sfc', $this->argument('output_directory'), Rom::BUILD);
                $rom->save($output_file);
                $this->info(sprintf('ROM Saved: %s', $output_file));

                return 0;
            }

            $crystals_ganon = $this->option('crystals_ganon');
            $crystals_ganon = $crystals_ganon === 'random' ? get_random_int(0, 7) : $crystals_ganon;
            $crystals_tower = $this->option('crystals_tower');
            $crystals_tower = $crystals_tower === 'random' ? get_random_int(0, 7) : $crystals_tower;
            $logic = [
                'none' => 'NoGlitches',
                'overworld_glitches' => 'OverworldGlitches',
                'major_glitches' => 'MajorGlitches',
                'no_logic' => 'NoLogic',
            ][$this->option('glitches')];

            $world = World::factory($this->option('state'), [
                'itemPlacement' => $this->option('item_placement'),
                'dungeonItems' => $this->option('dungeon_items'),
                'accessibility' => $this->option('accessibility'),
                'goal' => $this->option('goal'),
                'crystals.ganon' => $crystals_ganon,
                'crystals.tower' => $crystals_tower,
                'entrances' => 'none',
                'mode.weapons' => $this->option('weapons'),
                'tournament' => $this->option('tournament'),
                'spoil.Hints' => $this->option('hints'),
                'logic' => $logic,
                'item.pool' => $this->option('item_pool'),
                'item.functionality' => $this->option('item_functionality'),
                'enemizer.bossShuffle' => 'none',
                'enemizer.enemyShuffle' => 'none',
                'enemizer.enemyDamage' => 'default',
                'enemizer.enemyHealth' => 'default',
                'enemizer.potShuffle' => 'off',
            ]);

            $rand = new Randomizer([$world]);
            $rand->randomize();

            $world->writeToRom($rom);
            $rom->muteMusic((bool) $this->option('no-music') ?? false);
            $rom->setMenuSpeed($this->option('menu-speed'));

            $output_file = sprintf($filename, $hash, 'sfc');

            if (!($this->option('no-rom') ?? false)) {
                if ($this->option('sprite') && is_string($this->option('sprite')) && is_readable($this->option('sprite'))) {
                    $this->info("sprite");
                    try {
                        $zspr = new Zspr($this->option('sprite'));

                        $rom->write(0x80000, $zspr->getPixelData(), false);
                        $rom->write(0xDD308, substr($zspr->getPaletteData(), 0, 120), false);
                        $rom->write(0xDEDF5, substr($zspr->getPaletteData(), 120, 4), false);
                    } catch (\Exception $e) {
                        $this->error("Sprite not in ZSPR format");

                        return 4;
                    }
                }

                if ($this->option('tournament') ?? false) {
                    $rom->setTournamentType('standard');
                    $rom->rummageTable();
                }

                $rom->updateChecksum();
                $rom->save($output_file);

                $this->info(sprintf('ROM Saved: %s', $output_file));
            }

            if ($this->option('spoiler')) {
                $spoiler_file = sprintf($filename, $hash, 'json');

                file_put_contents($spoiler_file, json_encode($world->getSpoiler(), JSON_PRETTY_PRINT));
                $this->info(sprintf('Spoiler Saved: %s', $spoiler_file));
            }
        }
    }

    /**
     * Apply base patch to ROM file.
     *
     * @throws \Exception when base patch has no content.
     *
     * @return array
     */
    protected function resetPatch()
    {
        if ($this->reset_patch) {
            return $this->reset_patch;
        }

        if (is_readable(Rom::getJsonPatchLocation())) {
            $file_contents = file_get_contents(Rom::getJsonPatchLocation());

            if ($file_contents === false) {
                throw new \Exception('base patch not readable');
            }

            $patch_left = json_decode($file_contents, true);
        }

        $this->reset_patch = patch_merge_minify($patch_left ?? []);

        return $this->reset_patch;
    }
}
