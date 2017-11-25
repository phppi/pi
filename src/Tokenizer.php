<?php declare(strict_types = 1);

namespace PiCompiler;

use Nette\Tokenizer\Exception;
use Nette\Tokenizer\Stream;
use PiCompiler\Token\TokenInterface;
use Tracy\Debugger;

class Tokenizer
{
	public function source(string $source): Stream
	{
		Debugger::$maxLength = 10000;
		
		$tokenizer = new \Nette\Tokenizer\Tokenizer($this->getPattern());
		
		echo $source;
		
		try {
			return $tokenizer->tokenize($source);
		} catch (Exception $e) {
			dump($tokenizer);
			dump($e);
		}
	}
	
	private function getPattern(): array
	{
		return [
			TokenInterface::OPEN_TAG => '\<\?p',
			
			TokenInterface::VARIABLE => '\$\w+',
			
			TokenInterface::KEYWORD_NAMESPACE  => 'namespace',
			TokenInterface::KEYWORD_CLASS      => 'class',
			TokenInterface::KEYWORD_FINAL      => 'final',
			TokenInterface::KEYWORD_ABSTRACT   => 'abstract',
			TokenInterface::KEYWORD_DATA       => 'data',
			TokenInterface::KEYWORD_IF         => 'if',
			TokenInterface::KEYWORD_ELSE       => 'else',
			TokenInterface::KEYWORD_ELSEIF     => 'elseif',
			TokenInterface::KEYWORD_TRY        => 'try',
			TokenInterface::KEYWORD_CATCH      => 'catch',
			TokenInterface::KEYWORD_FINALY     => 'finaly',
			TokenInterface::KEYWORD_WHEN       => 'when',
			TokenInterface::KEYWORD_PUBLIC     => 'public',
			TokenInterface::KEYWORD_PROTECTED  => 'protected',
			TokenInterface::KEYWORD_PRIVATE    => 'private',
			TokenInterface::KEYWORD_STATIC     => 'static',
			TokenInterface::KEYWORD_RETURN     => 'return',
			TokenInterface::KEYWORD_YIELD      => 'yield',
			TokenInterface::KEYWORD_TRAIT      => 'trait',
			TokenInterface::KEYWORD_INTERFACE  => 'interface',
			TokenInterface::KEYWORD_CONST      => 'const',
			TokenInterface::KEYWORD_FOR        => 'for',
			TokenInterface::KEYWORD_FOREACH    => 'foreach',
			TokenInterface::KEYWORD_CONTINUE   => 'continue',
			TokenInterface::KEYWORD_BREAK      => 'break',
			TokenInterface::KEYWORD_WHILE      => 'while',
			TokenInterface::KEYWORD_DO         => 'do',
			TokenInterface::KEYWORD_DIE        => 'die',
			TokenInterface::KEYWORD_EXTENDS    => 'extends',
			TokenInterface::KEYWORD_IMPLEMENTS => 'implements',
			TokenInterface::KEYWORD_USE        => 'use',
			TokenInterface::KEYWORD_INSTANCEOF => 'instanceof',
			TokenInterface::KEYWORD_NEW        => 'new',
			TokenInterface::KEYWORD_PARENT     => 'parent',
			TokenInterface::KEYWORD_SELF       => 'self',
			TokenInterface::KEYWORD_THROW      => 'throw',
			
			TokenInterface::VALUE_STRING              => '\\\'\w+\\\'',
			TokenInterface::VALUE_STRING_ESCAPABLE    => '',

//			TokenInterface::KEYWORD_                     => '',
			TokenInterface::OPERATOR_IS_IDENTICAL     => '===',
			TokenInterface::OPERATOR_IS_NOT_IDENTICAL => '!==',
			
			TokenInterface::OPERATOR_AND                 => '\&\&',
			TokenInterface::OPERATOR_OR                  => '\|\|',
			TokenInterface::OPERATOR_SPACESHIP           => '<=>',
			TokenInterface::OPERATOR_IS_EQUAL            => '==',
			TokenInterface::OPERATOR_IS_GREATER_OR_EQUAL => '>=',
			TokenInterface::OPERATOR_IS_LOWER_OR_EQUAL   => '<=',
			
			TokenInterface::OPERATOR_IS_GREATER   => '>',
			TokenInterface::OPERATOR_IS_LOWER     => '<',
			TokenInterface::OPERATOR_IS_NOT_EQUAL => '!=',
			
			TokenInterface::TYPE_BOOL_ARRAY     => 'bool\[\]',
			TokenInterface::TYPE_CALLABLE_ARRAY => 'callable\[\]',
			TokenInterface::TYPE_FLOAT_ARRAY    => 'float\[\]',
			TokenInterface::TYPE_INT_ARRAY      => 'int\[\]',
			TokenInterface::TYPE_MIXED_ARRAY    => 'mixed\[\]',
			TokenInterface::TYPE_STRING_ARRAY   => 'string\[\]',
			TokenInterface::TYPE_OBJECT_ARRAY   => 'object\[\]',
			
			TokenInterface::TYPE_ARRAY    => 'array',
			TokenInterface::TYPE_BOOL     => 'bool',
			TokenInterface::TYPE_CALLABLE => 'callable',
			TokenInterface::TYPE_FLOAT    => 'float',
			TokenInterface::TYPE_INT      => 'int',
			TokenInterface::TYPE_MIXED    => 'mixed',
			TokenInterface::TYPE_STRING   => 'string',
			TokenInterface::TYPE_OBJECT   => 'object',
			TokenInterface::TYPE_VOID   => 'void',
			
			TokenInterface::VALUE_TRUE   => 'true',
			TokenInterface::VALUE_FALSE   => 'falser',
			TokenInterface::VALUE_NULL   => 'null',
			
			// Symbols
			
			TokenInterface::SYMBOL_DOUBLE_ARROW    => '=>',
			TokenInterface::SYMBOL_OBJECT_OPERATOR => '->',
			TokenInterface::SYMBOL_DOUBLE_COLON    => '::',
			TokenInterface::SYMBOL_DOUBLE_DOT      => ':',
			TokenInterface::SYMBOL_COMMA           => ',',
			TokenInterface::SYMBOL_ELLIPSIS        => '\.\.\.',
			
			TokenInterface::SYMBOL_ASSIGN            => '=',
			TokenInterface::SYMBOL_PARENTHESES_OPEN  => '\(',
			TokenInterface::SYMBOL_PARENTHESES_CLOSE => '\)',
			TokenInterface::SYMBOL_BRACKET_OPEN      => '\[',
			TokenInterface::SYMBOL_BRACKET_CLOSE     => '\]',
			TokenInterface::SYMBOL_BRACES_OPEN       => '{',
			TokenInterface::SYMBOL_BRACES_CLOSE      => '}',
			TokenInterface::SYMBOL_NEWLINE           => '\n',
			TokenInterface::SYMBOL_TAB               => '\t',
			
			TokenInterface::SPACE      => '\s+',
			TokenInterface::NUMBER     => '\d+',
			TokenInterface::STRING     => '\w+',
			
			// uNDEFIEND
			TokenInterface::_UNDEFINED => '.*',
		];
	}
}
