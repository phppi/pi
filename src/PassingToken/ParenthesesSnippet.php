<?php declare(strict_types = 1);

namespace PiCompiler\PassingToken;

class ParenthesesSnippet
{
	public $children = [];
	
	public function addChild($child)
	{
		$this->children[] = $child;
	}
	
	public function getChildren(): array
	{
		return $this->children;
	}
}
