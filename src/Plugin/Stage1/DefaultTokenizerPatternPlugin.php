<?php declare(strict_types = 1);

namespace PiCompiler\Plugin\Stage1;

use PiCompiler\Stage\Stage1\StageOnePluginInterface;

class DefaultTokenizerPatternPlugin implements StageOnePluginInterface
{
	public function get()
	{
		throw new \LogicException("TODO: Implement get() method in " . self::class);
	}
}
