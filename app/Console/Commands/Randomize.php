<?php namespace ALttP\Console\Commands;

use ALttP\Item;
use ALttP\Randomizer;
use ALttP\Rom;
use ALttP\World;
use Illuminate\Console\Command;

class Randomize extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'alttp:randomize {input_file : base rom to randomize}'
		. ' {output_directory : where to place randomized rom}'
		. ' {--unrandomized : do not apply randomization to the rom}'
		. ' {--vanilla : set game to vanilla item locations}'
		. ' {--debug : enable BAGE mode}'
		. ' {--spoiler : generate a spoiler file}'
		. ' {--difficulty=normal : set difficulty}'
		. ' {--logic=NoMajorGlitches : set logic}'
		. ' {--heartbeep=half : set heart beep speed}'
		. ' {--skip-md5 : do not validate md5 of base rom}'
		. ' {--trace : enable SRAM trace}'
		. ' {--seed= : set seed number}'
		. ' {--bulk=1 : generate multiple roms}'
		. ' {--goal=ganon : set game goal}'
		. ' {--mode=standard : set game mode}'
		. ' {--no-rom : no not generate output rom}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a randomized rom.';

	protected $reset_patch;

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if (!is_readable($this->argument('input_file'))) {
			return $this->error('Source File not readable');
		}

		if (!is_dir($this->argument('output_directory')) || !is_writable($this->argument('output_directory'))) {
			return $this->error('Target Directory not writable');
		}

		$bulk = ($this->option('seed') == null) ? $this->option('bulk') : 1;

		for ($i = 0; $i < $bulk; $i++) {
			$rom = new Rom($this->argument('input_file'));

			if (!$this->option('skip-md5') && !$rom->checkMD5()) {
				$rom->resize();

				$rom->applyPatch($this->resetPatch());
			}

			if (!$this->option('skip-md5') && !$rom->checkMD5()) {
				return $this->error('Could not Reset Rom');
			}

			$rom->setDebugMode($this->option('debug'));

			$rom->setHeartBeepSpeed($this->option('heartbeep'));

			$rom->setSRAMTrace($this->option('trace'));

			// break out for unrandomized/vanilla base game
			if ($this->option('vanilla')) {
				$rom = $this->setVanilla($rom);
				$output_file = sprintf('%s/alttp-%s-vanilla.sfc', $this->argument('output_directory'), Rom::BUILD);
				$rom->save($output_file);
				return $this->info(sprintf('Rom Saved: %s', $output_file));
			}
			if ($this->option('unrandomized')) {
				$output_file = sprintf('%s/alttp-%s.sfc', $this->argument('output_directory'), Rom::BUILD);
				$rom->save($output_file);
				return $this->info(sprintf('Rom Saved: %s', $output_file));
			}

			config(['game-mode' => $this->option('mode')]);

			$rand = new Randomizer($this->option('difficulty'), $this->option('logic'), $this->option('goal'));
			$rand->makeSeed($this->option('seed'));

			$rand->writeToRom($rom);

			$output_file = sprintf($this->argument('output_directory') . '/' . 'alttp - VT_%s_%s_%s_%s.sfc', $rand->getLogic(), $this->option('difficulty'), config('game-mode'), $rand->getSeed());
			if (!$this->option('no-rom', false)) {
				$rom->save($output_file);
				$this->info(sprintf('Rom Saved: %s', $output_file));
			}
			if ($this->option('spoiler')) {
				$spoiler_file = sprintf($this->argument('output_directory') . '/' . 'alttp - VT_%s_%s_%s_%s.txt', $rand->getLogic(), $this->option('difficulty'), config('game-mode'), $rand->getSeed());
				file_put_contents($spoiler_file, json_encode($rand->getSpoiler(), JSON_PRETTY_PRINT));
				$this->info(sprintf('Spoiler Saved: %s', $spoiler_file));
			}
		}
	}

	protected function setVanilla(Rom $rom) {
		$world = new World($this->option('difficulty'), $this->option('logic'), $this->option('goal'));
		$world->setVanilla();

		foreach ($world->getLocations() as $location) {
			$location->writeItem($rom);
		}

		$rom->setClockMode('off');
		$rom->setHardMode(0);

		$rom->setPyramidFairyChests(false);
		$rom->setSmithyQuickItemGive(false);

		$rom->setOpenMode(false);
		$rom->setSwordlessMode(false);
		$rom->setGanonAgahnimRng('vanilla');

		$rom->setMaxArrows();
		$rom->setMaxBombs();
		$rom->setStartingTime(0);

		$rom->setSeedString(str_pad("ZELDANODENSETSU", 21, ' '));

		return $rom;
	}

	protected function resetPatch() {
		if ($this->reset_patch) {
			return $this->reset_patch;
		}

		if (is_readable(public_path('js/base2current.json'))) {
			$patch_left = json_decode(file_get_contents(public_path('js/base2current.json')), true);
		}

		$this->reset_patch = patch_merge_minify($patch_left, $patch_right);

		return $this->reset_patch;
	}
}
