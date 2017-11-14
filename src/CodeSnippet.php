<?php declare(strict_types = 1);

namespace PiCompiler;

class CodeSnippet
{
	/** @var self|null */
	public $parent;
	public $children = [];
	
	public function __construct(?self $parent = null)
	{
		$this->parent = $parent;
		
		if ($parent !== null) {
			$parent->addChild($this);
		}
	}
	
	public function getParent(): ?self
	{
		return $this->parent;
	}
	
	public function addChild($child)
	{
		$this->children[] = $child;
	}
	
	public function getChildren(): array
	{
		return $this->children;
	}
}
