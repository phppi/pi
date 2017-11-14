<?php declare(strict_types = 1);

namespace PiCompiler\Token\Snippet;

class ClassToken implements SnippetInterface
{
	use \PiCompiler\Token\TParent;
	use \PiCompiler\Token\TChildren;
	
	private $name;
	
	public function __construct(string $name)
	{
		$this->name = $name;
	}
	
	
}
