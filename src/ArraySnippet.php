<?php declare(strict_types = 1);

namespace PiCompiler;

class ArraySnippet
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
