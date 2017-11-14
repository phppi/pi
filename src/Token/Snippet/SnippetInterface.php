<?php declare(strict_types = 1);

namespace PiCompiler\Token\Snippet;

use PiCompiler\Token\TokenInterface;

interface SnippetInterface extends TokenInterface
{
	public function addChild(TokenInterface $token);
}
