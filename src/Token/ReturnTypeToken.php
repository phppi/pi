<?php declare(strict_types = 1);

namespace PiCompiler\Token;

use PiCompiler\Token\Type\TypeInterface;

class ReturnTypeToken
{
	public $type;
	
	public function __construct(TypeInterface $type)
	{
		$this->type = $type;
	}
}
