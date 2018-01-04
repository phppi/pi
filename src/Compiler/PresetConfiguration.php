<?php declare(strict_types = 1);

namespace PiCompiler\Compiler;

class PresetConfiguration
{
	/** @var string */
	private $name;
	
	/** @var \PiCompiler\Stage\Stage1\StageOnePluginInterface[] */
	private $stageOne = [];
	
	
	public function __construct(string $name)
	{
		$this->name = $name;
	}
	
	/**
	 * @param \PiCompiler\Stage\Stage1\StageOnePluginInterface $stage1
	 */
	public function addStageOnePlugin(\PiCompiler\Stage\Stage1\StageOnePluginInterface $stage1): void
	{
		$this->stageOne[] = $stage1;
	}
	
	public function addStageTwoPlugin(\PiCompiler\Stage\Stage1\StageOnePluginInterface $stage1): void
	{
	
	}
	
	/**
	 * @return \PiCompiler\Stage\Stage1\StageOnePluginInterface[]
	 */
	public function getStageOne(): array
	{
		return $this->stageOne;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	
	
	
}
