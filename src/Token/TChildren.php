<?php declare(strict_types = 1);

namespace PiCompiler\Token;

trait TChildren
{
	public $children = [];
	
	public function addChild(TokenInterface $token)
	{
		$this->children[] = $token;
		$token->setParent($this);
	}
}
