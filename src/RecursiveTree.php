<?php declare(strict_types = 1);

namespace PiCompiler;

use Nette\Tokenizer\Stream;

class RecursiveTree
{
	public $tree;
	
	/** @var callable */
	public $deeperCondition;
	
	/** @var callable */
	public $backCondition;
	
	
	public function __construct(callable $deeperCondition, callable $backCondition)
	{
		$this->deeperCondition = $deeperCondition;
		$this->backCondition = $backCondition;
	}
	
	public function iterate(Stream $stream) {
		$temp = [];
		
		while ($token = $stream->nextToken()) {
			if (\call_user_func($this->deeperCondition, $token) === true) {
				$temp[] = new Stream($this->iterate($stream));
			} elseif (\call_user_func($this->backCondition, $token) === true) {
				return $temp;
			} else {
				$temp[] = $token;
			}
		}
		
		return new Stream($temp);
	}
}
