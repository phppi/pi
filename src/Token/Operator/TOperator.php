<?php declare(strict_types = 1);

namespace PiCompiler\Token\Operator;

trait TOperator
{
	public $left;
	public $right;
	
	
	public function __construct(
		$left,
		$right
	)
	{
		$this->left = $left;
		$this->right = $right;
	}
}
