<?php declare(strict_types = 1);

namespace PiCompiler\Token\Snippet;

use Nette\Tokenizer\Token;
use PiCompiler\Token\Type\TypeInterface;

class ClassToken implements SnippetInterface
{
	use \PiCompiler\Token\TParent;
	use \PiCompiler\Token\TChildren;
	private $name;
	public $final = false;
	public $abstract = false;
	
	public function __construct(string $name)
	{
		$this->name = $name;
	}
	
	public function modify(Token $token)
	{
		if ($token->type === TypeInterface::KEYWORD_FINAL) {
			$this->final = true;
		} elseif ($token->type === TypeInterface::KEYWORD_ABSTRACT) {
			$this->abstract = true;
		}
	}
}
