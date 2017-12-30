<?php declare(strict_types = 1);

namespace PiCompiler\Token;

use Nette\Tokenizer\Token;
use PiCompiler\Token\Modificator\VariableModificatorInterface;
use PiCompiler\Token\Type\TypeInterface;

class VariableToken implements TokenInterface
{
	use TParent;
	public const PUBLIC = 0;
	public const PROTECTED = 1;
	public const PRIVATE = 2;
	public $name;
	public $value;
	public $type;
	public $classMember = false;
	public $visibility = -1;
	public $local = false;
	/** @var VariableModificatorInterface[] */
	public $modificator = [];
	
	public function __construct(string $name, ?ValueInterface $value = null)
	{
		$this->name = $name;
		$this->value = $value;
	}
	
	public function addModificator(VariableModificatorInterface $mod)
	{
		$this->modificator[] = $mod;
	}
	
	public function modify(Token $token)
	{
		if ($token->type === TypeInterface::KEYWORD_PRIVATE) {
			$this->visibility = self::PRIVATE;
		} elseif ($token->type === TypeInterface::KEYWORD_PROTECTED) {
			$this->visibility = self::PROTECTED;
		} elseif ($token->type === TypeInterface::KEYWORD_PUBLIC) {
			$this->visibility = self::PUBLIC;
		}
	}
}
