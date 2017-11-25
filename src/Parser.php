<?php declare(strict_types = 1);

namespace PiCompiler;

use Nette\Tokenizer\Stream;
use Nette\Tokenizer\Token;
use PiCompiler\Token\Snippet\ClassToken;
use PiCompiler\Token\Snippet\SnippetToken;
use PiCompiler\Token\TokenInterface as TI;

class Parser
{
	const MAP_DELIMINITER
		= [
			TI::KEYWORD_CLASS => [
				TI::SYMBOL_BRACES_OPEN,
				TI::KEYWORD_INTERFACE,
				TI::KEYWORD_EXTENDS,
			],
		];
	private $skip = 0;
	
	public function parse(Stream $stream)
	{
		$mapValidNext = MapValidNext::getMap();
		
		$stream->reset();
		
		if ($stream->nextToken()->type !== TI::OPEN_TAG) {
			throw new \LogicException('No Pi file.');
		}
		
		$stream = $this->stripUselessTokens($stream);
		
		$this->checkNextToken($stream, $mapValidNext);
		
		$stream->reset();
		
		\dump('================');
		
		$progress = [];
		
		while ($stream->isNext()) {
//			if ($this->skip > 0) {
//				$stream->nextToken();
//				\dump('skip');
//				continue;
//			}
			
			$progress[] = $this->processToken(
				$stream->nextToken(),
				$stream
			);
			
			\dump($progress);
		}
	}
	
	public function processToken(Token $token, Stream $stream)
	{
		if ($token->type === TI::KEYWORD_CLASS) {
			$name = $stream->nextToken();
//			$this->skip++;
			
			return new ClassToken($name->value);
		} elseif (
			$token->type === TI::KEYWORD_ABSTRACT
			|| $token->type === TI::KEYWORD_FINAL
			|| $token->type === TI::KEYWORD_PUBLIC
			|| $token->type === TI::KEYWORD_PROTECTED
			|| $token->type === TI::KEYWORD_PRIVATE
		) {
			$modifed = $this->processToken($stream->nextToken(), $stream);
			$modifed->modify($token);
			
//			$this->skip++;
			
			return $modifed;
		} elseif (
			$token->type === TI::SYMBOL_BRACES_OPEN
		) {
			$stream->nextToken();
//			$this->skip++;
			
			$snippetToken = new SnippetToken();
			
			$snippetToken->addChild(
			
			);
			
			return $snippetToken;
		} else {
			\dump($token);
			
			die;
		}
		
		return;
	}
	
	/**
	 * @param \Nette\Tokenizer\Stream $stream
	 */
	private function stripUselessTokens(Stream $stream): Stream
	{
		$tokens = [];
		while ($token = $stream->nextToken()) {
			if (\in_array(
				$token->type,
				[
					TI::OPEN_TAG,
					TI::SPACE,
					TI::_UNDEFINED,
					TI::SYMBOL_NEWLINE,
					TI::_NULL,
					TI::SYMBOL_TAB,
				]
				,
				true
			)) {
				continue;
			}
			
			$tokens[] = $token;
		}
		
		return new Stream($tokens);
	}
	
	public function convertType(int $type)
	{
		return [
			TI::_NULL                        => 'TI::_NULL',
			TI::_UNDEFINED                   => 'TI::_UNDEFINED',
			TI::OPEN_TAG                     => 'TI::OPEN_TAG',
			TI::VARIABLE                     => 'TI::VARIABLE',
			TI::SPACE                        => 'TI::SPACE',
			TI::STRING                       => 'TI::STRING',
			TI::NUMBER                       => 'TI::NUMBER',
			TI::KEYWORD_NAMESPACE            => 'TI::KEYWORD_NAMESPACE',
			TI::KEYWORD_CLASS                => 'TI::KEYWORD_CLASS',
			TI::KEYWORD_FINAL                => 'TI::KEYWORD_FINAL',
			TI::KEYWORD_ABSTRACT             => 'TI::KEYWORD_ABSTRACT',
			TI::KEYWORD_DATA                 => 'TI::KEYWORD_DATA',
			TI::KEYWORD_IF                   => 'TI::KEYWORD_IF',
			TI::KEYWORD_ELSE                 => 'TI::KEYWORD_ELSE',
			TI::KEYWORD_ELSEIF               => 'TI::KEYWORD_ELSEIF',
			TI::KEYWORD_TRY                  => 'TI::KEYWORD_TRY',
			TI::KEYWORD_CATCH                => 'TI::KEYWORD_CATCH',
			TI::KEYWORD_FINALY               => 'TI::KEYWORD_FINALY',
			TI::KEYWORD_WHEN                 => 'TI::KEYWORD_WHEN',
			TI::KEYWORD_PUBLIC               => 'TI::KEYWORD_PUBLIC',
			TI::KEYWORD_PROTECTED            => 'TI::KEYWORD_PROTECTED',
			TI::KEYWORD_PRIVATE              => 'TI::KEYWORD_PRIVATE',
			TI::KEYWORD_STATIC               => 'TI::KEYWORD_STATIC',
			TI::KEYWORD_RETURN               => 'TI::KEYWORD_RETURN',
			TI::KEYWORD_YIELD                => 'TI::KEYWORD_YIELD',
			TI::KEYWORD_TRAIT                => 'TI::KEYWORD_TRAIT',
			TI::KEYWORD_INTERFACE            => 'TI::KEYWORD_INTERFACE',
			TI::KEYWORD_CONST                => 'TI::KEYWORD_CONST',
			TI::KEYWORD_FOR                  => 'TI::KEYWORD_FOR',
			TI::KEYWORD_FOREACH              => 'TI::KEYWORD_FOREACH',
			TI::KEYWORD_CONTINUE             => 'TI::KEYWORD_CONTINUE',
			TI::KEYWORD_BREAK                => 'TI::KEYWORD_BREAK',
			TI::KEYWORD_WHILE                => 'TI::KEYWORD_WHILE',
			TI::KEYWORD_DO                   => 'TI::KEYWORD_DO',
			TI::KEYWORD_DIE                  => 'TI::KEYWORD_DIE',
			TI::KEYWORD_EXTENDS              => 'TI::KEYWORD_EXTENDS',
			TI::KEYWORD_IMPLEMENTS           => 'TI::KEYWORD_IMPLEMENTS',
			TI::KEYWORD_USE                  => 'TI::KEYWORD_USE',
			TI::KEYWORD_INSTANCEOF           => 'TI::KEYWORD_INSTANCEOF',
			TI::KEYWORD_NEW                  => 'TI::KEYWORD_NEW',
			TI::KEYWORD_PARENT               => 'TI::KEYWORD_PARENT',
			TI::KEYWORD_SELF                 => 'TI::KEYWORD_SELF',
			TI::KEYWORD_THROW                => 'TI::KEYWORD_THROW',
			TI::KEYWORD_                     => 'TI::KEYWORD_',
			TI::SYMBOL_ASSIGN                => 'TI::SYMBOL_ASSIGN',
			TI::SYMBOL_PARENTHESES_OPEN      => 'TI::SYMBOL_PARENTHESES_OPEN',
			TI::SYMBOL_PARENTHESES_CLOSE     => 'TI::SYMBOL_PARENTHESES_CLOSE',
			TI::SYMBOL_BRACKET_OPEN          => 'TI::SYMBOL_BRACKET_OPEN',
			TI::SYMBOL_BRACKET_CLOSE         => 'TI::SYMBOL_BRACKET_CLOSE',
			TI::SYMBOL_BRACES_OPEN           => 'TI::SYMBOL_BRACES_OPEN',
			TI::SYMBOL_BRACES_CLOSE          => 'TI::SYMBOL_BRACES_CLOSE',
			TI::SYMBOL_DOUBLE_ARROW          => 'TI::SYMBOL_DOUBLE_ARROW',
			TI::SYMBOL_OBJECT_OPERATOR       => 'TI::SYMBOL_OBJECT_OPERATOR',
			TI::SYMBOL_DOUBLE_DOT            => 'TI::SYMBOL_DOUBLE_DOT',
			TI::SYMBOL_COMMA                 => 'TI::SYMBOL_COMMA',
			TI::SYMBOL_ELLIPSIS              => 'TI::SYMBOL_ELLIPSIS',
			TI::SYMBOL_DOUBLE_COLON          => 'TI::SYMBOL_DOUBLE_COLON',
			TI::SYMBOL_NEWLINE               => 'TI::SYMBOL_NEWLINE',
			TI::SYMBOL_TAB                   => 'TI::SYMBOL_TAB',
			TI::OPERATOR_AND                 => 'TI::OPERATOR_AND',
			TI::OPERATOR_OR                  => 'TI::OPERATOR_OR',
			TI::OPERATOR_SPACESHIP           => 'TI::OPERATOR_SPACESHIP',
			TI::OPERATOR_IS_EQUAL            => 'TI::OPERATOR_IS_EQUAL',
			TI::OPERATOR_IS_GREATER          => 'TI::OPERATOR_IS_GREATER',
			TI::OPERATOR_IS_GREATER_OR_EQUAL => 'TI::OPERATOR_IS_GREATER_OR_EQUAL',
			TI::OPERATOR_IS_LOWER            => 'TI::OPERATOR_IS_LOWER',
			TI::OPERATOR_IS_LOWER_OR_EQUAL   => 'TI::OPERATOR_IS_LOWER_OR_EQUAL',
			TI::OPERATOR_IS_IDENTICAL        => 'TI::OPERATOR_IS_IDENTICAL',
			TI::OPERATOR_IS_NOT_EQUAL        => 'TI::OPERATOR_IS_NOT_EQUAL',
			TI::OPERATOR_IS_NOT_IDENTICAL    => 'TI::OPERATOR_IS_NOT_IDENTICAL',
			TI::OPERATOR_                    => 'TI::OPERATOR_',
			TI::VALUE_STRING                 => 'TI::VALUE_STRING',
			TI::VALUE_STRING_ESCAPABLE       => 'TI::VALUE_STRING_ESCAPABLE',
			TI::TYPE_ARRAY                   => 'TI::TYPE_ARRAY',
			TI::TYPE_BOOL                    => 'TI::TYPE_BOOL',
			TI::TYPE_CALLABLE                => 'TI::TYPE_CALLABLE',
			TI::TYPE_FLOAT                   => 'TI::TYPE_FLOAT',
			TI::TYPE_INT                     => 'TI::TYPE_INT',
			TI::TYPE_MIXED                   => 'TI::TYPE_MIXED',
			TI::TYPE_STRING                  => 'TI::TYPE_STRING',
			TI::TYPE_OBJECT                  => 'TI::TYPE_OBJECT',
			TI::TYPE_BOOL_ARRAY              => 'TI::TYPE_BOOL_ARRAY',
			TI::TYPE_CALLABLE_ARRAY          => 'TI::TYPE_CALLABLE_ARRAY',
			TI::TYPE_FLOAT_ARRAY             => 'TI::TYPE_FLOAT_ARRAY',
			TI::TYPE_INT_ARRAY               => 'TI::TYPE_INT_ARRAY',
			TI::TYPE_MIXED_ARRAY             => 'TI::TYPE_MIXED_ARRAY',
			TI::TYPE_STRING_ARRAY            => 'TI::TYPE_STRING_ARRAY',
			TI::TYPE_OBJECT_ARRAY            => 'TI::TYPE_OBJECT_ARRAY',
			TI::TYPE_VOID                    => 'TI::TYPE_VOID',
			TI::VALUE_TRUE                   => 'TI::VALUE_TRUE',
			TI::VALUE_FALSE                  => 'TI::VALUE_FALSE',
			TI::VALUE_NULL                   => 'TI::VALUE_NULL',
		][$type];
	}
	
	/**
	 * @param $map
	 * @param $next
	 * @param $current
	 *
	 * @throws \LogicException
	 */
	private function throwExpectedMessage($map, $next, $current): void
	{
		$expected = \array_map(
			function ($item) {
				return $this->convertType($item);
			},
			$map
		);
		throw new \LogicException(
			"Unexpected {$this->convertType($next->type)} after {$this->convertType($current->type)}.\n\n"
			.
			\implode("\n", $expected) . "\n"
		);
	}
	
	/**
	 * @param \Nette\Tokenizer\Stream $stream
	 * @param                         $mapValidNext
	 *
	 * @throws \LogicException
	 */
	private function checkNextToken(Stream $stream, $mapValidNext): void
	{
		$current = $stream->nextToken();
		$next = $stream->nextToken();
		while (true) {
			dump('-------');
			dump($current);
			dump($next);
			
			$map = $mapValidNext[$current->type];
			
			if (\is_array($map) === false) {
				throw new \LogicException("Map for {$this->convertType($current->type)} is not defined.");
			}
			
			if (\in_array(
					$next->type,
					$map
				) === false) {
				$this->throwExpectedMessage($map, $next, $current);
			}
			
			$current = $next;
			$next = $stream->nextToken();
			
			if ($next === null) {
				break;
			}
		}
	}
}
