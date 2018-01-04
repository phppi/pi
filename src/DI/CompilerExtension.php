<?php declare(strict_types = 1);

namespace PiCompiler\DI;

use PiCompiler\Compiler\CompileCommand;
use PiCompiler\Compiler\Configuration;
use PiCompiler\Compiler\PresetConfiguration;
use Symfony\Component\Console\Application;

class CompilerExtension extends \Nette\DI\CompilerExtension
{
	protected $config;
	private const TAG_PLUGIN = 'plugin';
	private const TAG_PRESET = 'preset';
	
	public function loadConfiguration()
	{
		dump($this->getConfig());
		
		$this->config = $this->getConfig();
	}
	
	/**
	 * @throws \Nette\InvalidArgumentException
	 * @throws \Nette\InvalidStateException
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		
		$plugins = [];
		
		// -----------------------------------------
		// Symfony Console
		// -----------------------------------------
		
		$console = $builder->addDefinition($this->prefix("console"))
			->setType(Application::class)
			->setArguments(
				[
					'PHP Pi Compiler',
					'0.1'
				]
			);
		
		$command = $builder->addDefinition($this->prefix('command.compile'))
			->setType(CompileCommand::class);
		
		$console->addSetup('add', [$command]);
		
		// -----------------------------------------
		// Base Configuration
		// -----------------------------------------
		$configuration = $builder->addDefinition($this->prefix('configuration'))
			->setType(Configuration::class)
			->setArguments(
				[
					$this->config['debugMode'] ?? false,
					$this->config['default-preset'],
				]
			);
		
		// -----------------------------------------
		// Presets Configuration
		// -----------------------------------------
		$i = 0;
		foreach ($this->config['presets'] as $name => $presetConfig) {
			\dump($presetConfig);
			$presetName = $this->prefix(self::TAG_PRESET . "." . $i);
			
			$preset = $builder->addDefinition($presetName)
				->addTag(self::TAG_PRESET)
				->setType(PresetConfiguration::class)
				->setArguments(
					[
						$name,
					]
				);
			
			$stages = $presetConfig['stages'];
			
			foreach ($stages[1] as $pluginIndex => $plugin) {
			
				if (isset($plugins[$plugin]) === false) {
					$plugins[$plugin] = $builder->addDefinition($this->prefix(self::TAG_PLUGIN . "." . $pluginIndex))
						->addTag(self::TAG_PLUGIN)
						->setType($plugin);
				}
				
				$preset->addSetup('addStageOnePlugin', [$plugins[$plugin]]);
			}

			
			$configuration->addSetup('addPreset', [$preset]);

			$i++;
		}
	}
}
