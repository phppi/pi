<?php declare(strict_types = 1);

namespace PiCompiler\Compiler;

use PiCompiler\Compiler\Exception\UndefinedPresetException;

class Configuration
{
	/** @var \PiCompiler\Compiler\PresetConfiguration[] */
	private $presets;
	
	/** @var bool */
	private $debug;
	
	/** @var string */
	private $defaultPreset;
	
	public function __construct(bool $debug, string $defaultPreset)
	{
		$this->debug = $debug;
		$this->defaultPreset = $defaultPreset;
	}
	
	/**
	 * @throws \PiCompiler\Compiler\Exception\UndefinedPresetException
	 */
	public function getPreset(string $name): PresetConfiguration
	{
		if (isset($this->presets[$name]) === false) {
			throw new UndefinedPresetException("Preset '$name' is not defined.");
		}
		
		return $this->presets[$name];
	}
	
	public function getDebug(): bool
	{
		return $this->debug;
	}
	
	public function addPreset(PresetConfiguration $preset)
	{
		$this->presets[$preset->getName()] = $preset;
	}
	
	public function getDefaultPreset(): PresetConfiguration
	{
		return $this->getPreset($this->defaultPreset);
	}
}
