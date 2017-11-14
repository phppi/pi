<?php declare(strict_types = 1);

namespace PiCompiler\Token\Snippet;

use PiCompiler\Token\TChildren;
use PiCompiler\Token\TParent;
use PiCompiler\Token\ValueInterface;

class ConditionToken implements SnippetInterface, ValueInterface
{
	use TParent;
	use TChildren;
}
