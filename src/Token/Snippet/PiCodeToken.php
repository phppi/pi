<?php declare(strict_types = 1);

namespace PiCompiler\Token\Snippet;

use PiCompiler\Token\TChildren;
use PiCompiler\Token\TokenInterface;

class PiCodeToken implements SnippetInterface
{
	use TChildren;
	
	public function getParent(): ?\PiCompiler\Token\TokenInterface
	{
		return null;
	}
	
	public function setParent(?TokenInterface $token)
	{
		throw new \LogicException(self::class . ' can\'t have parent');
	}
}
