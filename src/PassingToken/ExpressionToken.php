<?php declare(strict_types = 1);

namespace PiCompiler\PassingToken;

class ExpressionToken
{
	public $value;
	
	public function __construct($value)
	{
		$this->value = $value;
	}
	
}
