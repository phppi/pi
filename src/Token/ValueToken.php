<?php declare(strict_types = 1);

namespace PiCompiler\Token;

use PiCompiler\Token\Type\TypeInterface;

class ValueToken
{
	public $value;
	public $type;
	
	public function __construct($value, TypeInterface $type)
	{
	
	}
}
