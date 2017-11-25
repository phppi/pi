<?php declare(strict_types = 1);

namespace PiCompiler;

use PiCompiler\PassingToken\ExpressionToken;
use PiCompiler\PassingToken\ParenthesesSnippet;
use PiCompiler\Token\Keywords\ConstantEncapsedStringToken;
use PiCompiler\Token\Keywords\DoubleArrowToken;
use PiCompiler\Token\Keywords\ReturnToken;
use PiCompiler\Token\Modificator\AbstractToken;
use PiCompiler\Token\Modificator\FinalToken;
use PiCompiler\Token\Modificator\ModificatorInterface;
use PiCompiler\Token\Modificator\PrivateToken;
use PiCompiler\Token\Modificator\ProtectedToken;
use PiCompiler\Token\Modificator\PublicToken;
use PiCompiler\Token\Modificator\VariableModificatorInterface;
use PiCompiler\Token\Operator\IsEqualOperatorType;
use PiCompiler\Token\Operator\IsGreaterOrEqualOperatorType;
use PiCompiler\Token\Operator\IsIdenticalOperatorType;
use PiCompiler\Token\Operator\IsLowerOrEqualOperatorType;
use PiCompiler\Token\Operator\IsNotEqualOperatorType;
use PiCompiler\Token\Operator\IsNotIdenticalOperatorType;
use PiCompiler\Token\Operator\ModificatorType;
use PiCompiler\Token\Operator\OperatorInterface;
use PiCompiler\Token\Operator\SpaceshipOperatorType;
use PiCompiler\Token\ReturnTypeToken;
use PiCompiler\Token\Snippet\ClassToken;
use PiCompiler\Token\Snippet\PiCodeToken;
use PiCompiler\Token\Snippet\SnippetInterface;
use PiCompiler\Token\Type\ArrayToken;
use PiCompiler\Token\Type\BoolToken;
use PiCompiler\Token\Type\CallableToken;
use PiCompiler\Token\Type\FloatToken;
use PiCompiler\Token\Type\IntToken;
use PiCompiler\Token\Type\PiTypes\ArrayBoolToken;
use PiCompiler\Token\Type\PiTypes\ArrayCallableToken;
use PiCompiler\Token\Type\PiTypes\ArrayFloatToken;
use PiCompiler\Token\Type\PiTypes\ArrayIntToken;
use PiCompiler\Token\Type\PiTypes\ArrayStringToken;
use PiCompiler\Token\Type\StringToken;
use PiCompiler\Token\Type\TypeInterface;
use PiCompiler\Token\Value\IntValueToken;
use PiCompiler\Token\ValueToken;
use PiCompiler\Token\VariableToken;
use Tracy\Debugger;

class OldParser
{
	private $tokens;
	private $count;
	private $iterator = 2;
	
	private $waitForCloseTags = 0;
	
	private const CLOSE_TAG
		= [
			'('                            => ')',
			self::CODE_SYMBOL_SNIPPET_OPEN => self::CODE_SYMBOL_SNIPPET_CLOSE,
		];
	
	const CODE_SYMBOL_SNIPPET_OPEN = '{';
	const CODE_SYMBOL_SNIPPET_CLOSE = '}';
	const CODE_SYMBOL_BRACKETS_OPEN = '[';
	const CODE_SYMBOL_BRACKETS_CLOSE = ']';
	
	const CODE_SYMBOL_PARENTHESES_OPEN = '(';
	const CODE_SYMBOL_PARENTHESES_CLOSE = ')';
	const CODE_SYMBOL_ASSIGN = '=';
	
	const SYMBOL_SEMICOLON = ';';
	
	const SYMBOL_COMMA = ",";
	
	
	
	
	public function parse(array $tokens)
	{
		Debugger::$maxDepth = 0;
		Debugger::$maxLength = 30000;
		
		$this->tokens = $tokens;
		
		$this->count = count($this->tokens);
		
		$piCode = $this->processOpenTag(
			$this->tokens[0][0] ?? null,
			$this->tokens[1][0] ?? null,
			$this->tokens[1][1] ?? null
		);
		
		// unset open tag
		unset($this->tokens[0]);
		unset($this->tokens[1]);
		
		// Convert tokens to better read tokens
		$this->processClearUselessSymbols();
		$this->processTokenization();
		
		$this->processTokenRelation();
		
		$this->processParentheses();
		$this->processBrackets();
		
		
		// organize code into snippets
		$codeSnippet = $this->processCodeSnippets();
		
		dump($codeSnippet);
		die;
	}
	
	public function processTokens()
	{
	
	}
	
	public function processSymbol(
		$prev,
		string $symbol
	)//: TokenInterface
	{
		dump($prev);
		dump($symbol);
		
	}
	
	public function processOpenTag(
		?int $type,
		?int $nextType,
		?string $nextValue
	): PiCodeToken {
		if ($type === T_OPEN_TAG && $nextType === T_STRING && $nextValue === 'p') {
			return new PiCodeToken();
		}
		
		throw new CompiledFileIsNotPiException();
	}
	
	/**
	 * @param $count
	 * @param $piCode
	 *
	 * @throws \PiCompiler\ParseErrorException
	 */
	private function processCycle(
		SnippetInterface $piCode
	) {
		$prev = null;
		
		$queue = [];
		
		for ($this->iterator; $this->iterator < $this->count; $this->iterator++) {
			$item = $this->tokens[$this->iterator];
			if (is_array($item) === false) {
				if ($item === self::CODE_SYMBOL_SNIPPET_OPEN) {
					$this->waitForCloseTags++;
					if ($prev instanceof SnippetInterface) {
						$this->processCycle($prev);
					}
				} elseif ($item === self::CODE_SYMBOL_SNIPPET_CLOSE) {
					if ($this->waitForCloseTags === 0) {
						throw new ParseErrorException('Close symbol doesnt match');
					}
					
					$this->waitForCloseTags--;
					
					return $piCode;
				}
				
				dump($item);
				
			} else {
				[
					$type,
					$value,
				]
					= $this->tokens[$this->iterator];
				[
					$nextType,
					$nextValue,
				]
					= $this->getNext();
				
				$entity = null;
				
				if ($type === T_CLASS) {
					if ($nextType !== T_STRING) {
						throw new ParseErrorException('Class name must be string');
					}
					
					$entity = new ClassToken($nextValue);
					$this->skip();
				} elseif ($type === T_PRIVATE) {
					$queue[] = new PrivateToken();
					continue;
				} elseif ($type === T_PUBLIC) {
					$queue[] = new PublicToken();
					continue;
				} elseif ($type === T_PROTECTED) {
					$queue[] = new ProtectedToken();
					continue;
				} elseif ($type === T_ABSTRACT) {
					$queue[] = new AbstractToken();
					continue;
				} elseif ($type === T_FINAL) {
					$queue[] = new FinalToken();
					continue;
					
				} elseif ($type === T_VARIABLE) {
//					dump($piCode);
//					dump($queue);
					dump($nextType);
					
					$val = null;
					
					if ($this->getNext() === self::CODE_SYMBOL_ASSIGN) {
						$this->skip();
						$this->skip();
						
						$val = $this->processExpresion();
					}
					
					$entity = new VariableToken(
						$value,
						$val
					);
					
					foreach ($queue as $item) {
						if ($item instanceof VariableModificatorInterface === false) {
							throw new ParseErrorException('Cant set ' . get_class($item) . ' to variable');
						}
						
						$entity->addModificator($item);
					}
					$queue = [];
					
				} else {
					dump(token_name($type));
				}
				
				$piCode->addChild($entity ?? new IntToken());
				
				$prev = $entity;
			}
		}
	}
	
	public function processExpresion()
	{
		$expr = [];
		
		$prev = null;
		
		for ($this->iterator; $this->iterator < $this->count; $this->iterator++) {
			$item = $this->tokens[$this->iterator];
			
			[
				$type,
				$value,
			]
				= $item;
			
			dump($item);
			
			if ($type === T_LNUMBER) {
				$expr[] = new IntValueToken($value);
			} elseif ($type === T_STRING) {
				$expr[] = new IntValueToken($value);
			} elseif ($type === T_NUM_STRING) {
				$expr[] = new IntValueToken($value);
			} elseif ($type === T_EQUAL) {
				$expr[] = new IntValueToken($value);
			} elseif ($type === T_SPACESHIP) {
				$expr[] = new SpaceshipOperatorType();
			} elseif ($type === T_IS_NOT_IDENTICAL) {
				$expr[] = new IsNotIdenticalOperatorType();
			} elseif ($type === T_IS_IDENTICAL) {
				$expr[] = new IsIdenticalOperatorType();
			} elseif ($type === T_IS_NOT_EQUAL) {
				$expr[] = new IsNotEqualOperatorType();
			} elseif ($type === T_IS_EQUAL) {
				$expr[] = new IsEqualOperatorType();
			} elseif ($type === T_IS_GREATER_OR_EQUAL) {
				$expr[] = new IsGreaterOrEqualOperatorType();
			} elseif ($type === T_IS_SMALLER_OR_EQUAL) {
				$expr[] = new IsLowerOrEqualOperatorType();
			} else {
				
				$expr = $this->optimizeExpression($expr);
				
				die;
			}
		}
	}
	
	public function optimizeExpression(
		array $expr
	): array {
		dump($expr);
		
		foreach ($expr as $index => $value) {
			if ($value instanceof OperatorInterface) {
				$left = $expr[$index - 1];
				$right = $expr[$index + 1];
				
				unset($expr[$index - 1], $expr[$index + 1]);
				
				$value->left = $left;
				$value->right = $right;
			}
		}
		
		dump($expr);
		
		die;
	}
	
	public function skip()
	{
		$this->iterator++;
	}
	
	public function getNext(
		$next = 1
	) {
		return $this->tokens[$this->iterator + $next];
	}
	
	private function processTokenization(): void
	{
		foreach ($this->tokens as $index => $token) {
			if (\is_string($token)) {
				continue;
//
//				if ($token === "=") {
//					$this->tokens[$index] = new AssignTok
//				}
			
			}
			$this->tokens[$index][0] = \token_name($token[0]);
			
			$nextIndex = $index + 1;
			$nextToken = $this->tokens[$nextIndex];
			
			[
				$nextType,
				$nextValue,
			]
				= $nextToken;
			
			if ($token[0] === \T_CLASS) {
				
				
				if ($nextType !== T_STRING) {
					throw new ParseErrorException('Class name must be string');
				}
				
				$this->tokens[$index] = new ClassToken($nextValue);
				
				unset($this->tokens[$nextIndex]);
				
				// Modificators
			} elseif ($token[0] === T_PUBLIC) {
				$this->tokens[$index] = new PublicToken();
			} elseif ($token[0] === T_PROTECTED) {
				$this->tokens[$index] = new ProtectedToken();
			} elseif ($token[0] === T_PRIVATE) {
				$this->tokens[$index] = new PrivateToken();
			} elseif ($token[0] === T_FINAL) {
				$this->tokens[$index] = new FinalToken();
			} elseif ($token[0] === T_ABSTRACT) {
				$this->tokens[$index] = new AbstractToken();
			} elseif ($token[0] === \T_CALLABLE) {
				$this->tokens[$index] = new CallableToken();
			} elseif ($token[0] === \T_DOUBLE_ARROW) {
				$this->tokens[$index] = new DoubleArrowToken();
			} elseif ($token[0] === \T_RETURN) {
				$this->tokens[$index] = new ReturnToken();
			} elseif ($token[0] === \T_CONSTANT_ENCAPSED_STRING) {
				$this->tokens[$index] = new ValueToken($token[1], new StringToken());
//			} elseif ($token[0] === T_STATIC) {$this->tokens[$index] = new StaticToken();
			} elseif ($token[0] === T_LNUMBER) {
				$this->tokens[$index] = new ValueToken($token[1], new IntToken());
			} elseif ($token[0] === T_DNUMBER) {
				$this->tokens[$index] = new ValueToken($token[1], new FloatToken());
			} elseif ($token[0] === \T_STRING) {
				
				if ($nextToken === "[") {
					if ($this->tokens[$nextIndex + 1] === "]") {
						if ($token[1] === "int") {
							$this->tokens[$index] = new ArrayIntToken();
						} elseif ($token[1] === "string") {
							$this->tokens[$index] = new ArrayStringToken();
						} elseif ($token[1] === "bool") {
							$this->tokens[$index] = new ArrayBoolToken();
						} elseif ($token[1] === "callable") {
							$this->tokens[$index] = new ArrayCallableToken();
						} elseif ($token[1] === "float") {
							$this->tokens[$index] = new ArrayFloatToken();
						} else {
							throw new \LogicException("Invalid type {$token[1]}[]");
						}
						
						unset($this->tokens[$index + 1]);
						unset($this->tokens[$index + 2]);
					}
				} elseif ($token[1] === "int") {
					$this->tokens[$index] = new IntToken();
				} elseif ($token[1] === "string") {
					$this->tokens[$index] = new StringToken();
				} elseif ($token[1] === "bool") {
					$this->tokens[$index] = new BoolToken();
				} elseif ($token[1] === "callable") {
					$this->tokens[$index] = new CallableToken();
				} elseif ($token[1] === "float") {
					$this->tokens[$index] = new FloatToken();
				} else {
					$this->tokens[$index] = new ExpressionToken($token[1]);
				}

//				$this->tokens[$index] = new ValueToken($token[1], new StringToken());
			} elseif ($token[0] === T_VARIABLE) {
				$this->tokens[$index] = new VariableToken($token[1]);
			} elseif ($token[0] === \T_ARRAY) {
				$this->tokens[$index] = new ArrayToken();
				
			} elseif ($token[0] === \T_IS_IDENTICAL) {
				$this->tokens[$index] = new IsIdenticalOperatorType();
			}
		}
		$this->repairIndex();
		
	}
	
	/**
	 * @return array
	 */
	private function repairIndex(): array
	{
		return $this->tokens = \array_values($this->tokens);
	}
	
	/**
	 * @return \PiCompiler\CodeSnippet
	 * @throws \LogicException
	 */
	private function processCodeSnippets(): \PiCompiler\CodeSnippet
	{
		$pointer = $codeSnippet = new CodeSnippet();
		foreach ($this->tokens as $index => $token) {
			if ($pointer === null) {
				throw new \LogicException('No parent');
			}
			
			if ($token === self::CODE_SYMBOL_SNIPPET_OPEN) {
				$pointer = new CodeSnippet($pointer);
			} elseif ($token === self::CODE_SYMBOL_SNIPPET_CLOSE) {
				$pointer = $pointer->getParent();
			} else {
				$pointer->addChild($token);
			}
		}
		
		return $codeSnippet;
	}
	
	private function processTokenRelation(): void
	{
		foreach ($this->tokens as $index => $token) {
			$next = $this->tokens[$index + 1];
			if ($token instanceof VariableToken) {
				
				$i = $index - 1;
				while ($prev = $this->tokens[$i]) {
					if ($prev instanceof ModificatorInterface === false) {
						break;
					}
					$token->addModificator($prev);
					unset($this->tokens[$i]);
					
					$i--;
				}
				
				$next = $this->tokens[$index + 1];
				
				if ($next instanceof ValueToken) {
					$token->value = $next;
					
					unset($this->tokens[$index + 1]);
				}
			}
			
			if ($token instanceof ReturnToken) {
				$next = $this->tokens[$index + 1];
				
				if ($next instanceof ValueToken) {
				}
			}
			
			if ($token === ":") {
				if ($next instanceof TypeInterface) {
					$this->tokens[$index] = new ReturnTypeToken($next);
					
					unset ($this->tokens[$index + 1]);
				}
			}
		}
		$this->repairIndex();
	}
	
	private function processParentheses(): void
	{
		$count = count($this->tokens);
		$pointer = null;
		for ($i = 0; $i < $count; $i++) {
			$token = $this->tokens[$i];
			if ($token === self::CODE_SYMBOL_PARENTHESES_OPEN) {
				$this->tokens[$i] = $pointer = new ParenthesesSnippet();
			} elseif ($token === self::CODE_SYMBOL_PARENTHESES_CLOSE) {
				$pointer = null;
				unset($this->tokens[$i]);
			} elseif ($pointer !== null) {
				$pointer->addChild($token);
				unset($this->tokens[$i]);
			}
		}
		$this->repairIndex();
	}
	
	private function processBrackets(): void
	{
		$count = count($this->tokens);
		
		$pointer = null;
		for ($i = 0; $i < $count; $i++) {
			$token = $this->tokens[$i];
			
			if ($token === self::CODE_SYMBOL_BRACKETS_OPEN) {
				$this->tokens[$i] = $pointer = new ArraySnippet();
			} elseif ($token === self::CODE_SYMBOL_BRACKETS_CLOSE) {
				$pointer = null;
				unset($this->tokens[$i]);
			} elseif ($pointer !== null) {
				$pointer->addChild($token);
				unset($this->tokens[$i]);
			}
		}
		
		$this->repairIndex();
	}
	
	public function processClearUselessSymbols()
	{
		foreach ($this->tokens as $index => $token) {
			if ($token === self::SYMBOL_SEMICOLON
				|| $token === self::SYMBOL_COMMA
			) {
				unset($this->tokens[$index]);
			}
		}
		$this->repairIndex();
	}
}
