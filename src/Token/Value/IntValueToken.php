<?php declare(strict_types = 1);

namespace PiCompiler\Token\Value;

use PiCompiler\Token\ValueInterface;

class IntValueToken implements ValueInterface
{
	public $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
}
