<?php declare(strict_types = 1);

namespace PiCompiler\Token;

interface TokenInterface
{
	public function getParent(): ?TokenInterface;
	
	public function setParent(?TokenInterface $token);

//	/** @return \PiCompiler\Token\TokenInterface[] */
//	public function getChildren(): array;
	
	public const _NULL = null;
	public const _UNDEFINED = -1;
	public const OPEN_TAG = 0;
	public const VARIABLE = 200;
	public const SPACE = 1;
	public const STRING = 2;
	public const NUMBER = 1000;
	public const KEYWORD_NAMESPACE = 3;
	public const KEYWORD_CLASS = 4;
	public const KEYWORD_FINAL = 5;
	public const KEYWORD_ABSTRACT = 6;
	public const KEYWORD_DATA = 7;
	public const KEYWORD_IF = 8;
	public const KEYWORD_ELSE = 9;
	public const KEYWORD_ELSEIF = 10;
	public const KEYWORD_TRY = 11;
	public const KEYWORD_CATCH = 12;
	public const KEYWORD_FINALY = 13;
	public const KEYWORD_WHEN = 14;
	public const KEYWORD_PUBLIC = 15;
	public const KEYWORD_PROTECTED = 16;
	public const KEYWORD_PRIVATE = 17;
	public const KEYWORD_STATIC = 18;
	public const KEYWORD_RETURN = 19;
	public const KEYWORD_YIELD = 20;
	public const KEYWORD_TRAIT = 21;
	public const KEYWORD_INTERFACE = 22;
	public const KEYWORD_CONST = 23;
	public const KEYWORD_FOR = 24;
	public const KEYWORD_FOREACH = 25;
	public const KEYWORD_CONTINUE = 25;
	public const KEYWORD_BREAK = 25;
	public const KEYWORD_WHILE = 25;
	public const KEYWORD_DO = 25;
	public const KEYWORD_DIE = 25;
	public const KEYWORD_EXTENDS = 25;
	public const KEYWORD_IMPLEMENTS = 25;
	public const KEYWORD_USE = 25;
	public const KEYWORD_INSTANCEOF = 25;
	public const KEYWORD_NEW = 25;
	public const KEYWORD_PARENT = 25;
	public const KEYWORD_SELF = 25;
	public const KEYWORD_THROW = 25;
	public const KEYWORD_ = 25;
	// Symbols
	// 50-99
	public const SYMBOL_ASSIGN = 50;
	public const SYMBOL_PARENTHESES_OPEN = 51;
	public const SYMBOL_PARENTHESES_CLOSE = 52;
	public const SYMBOL_BRACKET_OPEN = 53;
	public const SYMBOL_BRACKET_CLOSE = 54;
	public const SYMBOL_BRACES_OPEN = 55;
	public const SYMBOL_BRACES_CLOSE = 56;
	public const SYMBOL_DOUBLE_ARROW = 57;
	public const SYMBOL_OBJECT_OPERATOR = 58;
	public const SYMBOL_DOUBLE_DOT = 59;
	public const SYMBOL_COMMA = 60;
	public const SYMBOL_ELLIPSIS = 61;
	public const SYMBOL_DOUBLE_COLON = 62;
	public const SYMBOL_NEWLINE = 63;
	public const SYMBOL_TAB = 64;
	// OPERATORS
	// 100-199
	public const OPERATOR_AND = 101;
	public const OPERATOR_OR = 102;
	public const OPERATOR_SPACESHIP = 103;
	public const OPERATOR_IS_EQUAL = 104;
	public const OPERATOR_IS_GREATER = 105;
	public const OPERATOR_IS_GREATER_OR_EQUAL = 106;
	public const OPERATOR_IS_LOWER = 107;
	public const OPERATOR_IS_LOWER_OR_EQUAL = 108;
	public const OPERATOR_IS_IDENTICAL = 109;
	public const OPERATOR_IS_NOT_EQUAL = 110;
	public const OPERATOR_IS_NOT_IDENTICAL = 111;
	public const OPERATOR_ = 112;
	// Values
	// 300-399
	public const VALUE_STRING = 300;
	public const VALUE_STRING_ESCAPABLE = 301;
	public const VALUE_TRUE = 302;
	public const VALUE_FALSE = 303;
	public const VALUE_NULL = 304;
	
	// Types
	// 400 - 499
	public const TYPE_ARRAY = 400;
	public const TYPE_BOOL = 401;
	public const TYPE_CALLABLE = 402;
	public const TYPE_FLOAT = 403;
	public const TYPE_INT = 404;
	public const TYPE_MIXED = 405;
	public const TYPE_STRING = 406;
	public const TYPE_OBJECT = 407;
	public const TYPE_BOOL_ARRAY = 411;
	public const TYPE_CALLABLE_ARRAY = 412;
	public const TYPE_FLOAT_ARRAY = 413;
	public const TYPE_INT_ARRAY = 414;
	public const TYPE_MIXED_ARRAY = 415;
	public const TYPE_STRING_ARRAY = 416;
	public const TYPE_OBJECT_ARRAY = 417;
	public const TYPE_VOID = 418;
}
