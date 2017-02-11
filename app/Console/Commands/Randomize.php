<?php namespace ALttP\Console\Commands;

use Illuminate\Console\Command;
use ALttP\Rom;
use ALttP\Randomizer;

class Randomize extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'alttp:randomize {input_file} {output_directory} {--debug} {--spoiler} {--rules=v8} {--mode=NoMajorGlitches} {--heartbeep=half} {--trace}  {--seed=} {--bulk=1}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a randomized rom.';

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

			$basejson = file_get_contents(public_path('js/base2current.json'));

			$rom->applyJSONPatches($basejson);
			if (!$rom->checkMD5()) {
				return $this->error('Error patching vanilla ROM');
			}

			$rand = new Randomizer($this->option('rules'), $this->option('mode'));
			$rand->makeSeed($this->option('seed'));

			$rom->setHeartBeepSpeed($this->option('heartbeep'));
			if ($this->option('trace')) {
				$rom->setSRAMTrace(true);
			}

			if ($this->option('debug')) {
				$rom->setDebugMode(true);
			}

			$rand->writeToRom($rom);

			$output_file = sprintf($this->argument('output_directory') . '/' . $rand->config('output.file.name', 'alttp - p.%s_%s.sfc'), $rand->getLogic(), $rand->getSeed());
			$rom->save($output_file);
			if ($this->option('spoiler')) {
				$spoiler_file = sprintf($this->argument('output_directory') . '/' . $rand->config('output.file.spoiler', 'alttp - p.%s_%s.txt'), $rand->getLogic(), $rand->getSeed());
				file_put_contents($spoiler_file, json_encode($rand->getSpoiler(), JSON_PRETTY_PRINT));
			}
			$this->info(sprintf('Rom Saved: %s', $output_file));
		}
	}
}
