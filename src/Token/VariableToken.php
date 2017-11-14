<?php declare(strict_types = 1);

namespace PiCompiler\Token;

use PiCompiler\Token\Modificator\VariableModificatorInterface;

class VariableToken implements TokenInterface
{
	use TParent;
	
	public $name;
	public $value;
	public $type;
	
	/** @var VariableModificatorInterface[] */
	public $modificator = [];
	
	public function __construct(string $name, ?ValueInterface $value = null)
	{
		$this->name = $name;
		$this->value = $value;
	}
	
	public function addModificator(VariableModificatorInterface $mod)
	{
		$this->modificator[] = $mod;
	}
}
