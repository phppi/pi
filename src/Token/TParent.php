<?php declare(strict_types = 1);

namespace PiCompiler\Token;

trait TParent
{
	/** @var \PiCompiler\Token\TokenInterface|null */
	private $parent;
	
	public function getParent(): ?\PiCompiler\Token\TokenInterface
	{
		return $this->parent;
	}
	
	public function setParent(?\PiCompiler\Token\TokenInterface $parent): void
	{
		$this->parent = $parent;
	}
}
