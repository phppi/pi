<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

//\Tracy\Debugger::enable(true);

$source = file_get_contents(__DIR__ . '/../example/MyLittleClass.phpp');

$tokenizer = new \PiCompiler\Tokenizer();

$stream = $tokenizer->source($source);

$parser = new \PiCompiler\Parser();
$parser->parse($stream);

die;

//$tokens = $parser->parse($source);
$phpTokens = token_get_all($source);

dump($source);

$phpTokens = array_filter($phpTokens, function ($item) {
	return $item[0] !== T_WHITESPACE;
});

$phpTokens = array_values($phpTokens);

$parser = new \PiCompiler\OldParser();

$parser->parse($phpTokens);

die;


const PI_OPEN_TAG = 21001;

const PI_PARENTHESES_OPEN = 21010;
const PI_PARENTHESES_CLOSE = 21011;


$stream = [];

$count = count ($phpTokens);

for ($i = 0; $i < $count; $i++) {
	[$type, $value, $line] = $phpTokens[$i];
	[$nextType, $nextValue, $nextLine] = $phpTokens[$i + 1];
	
	if($type === T_WHITESPACE)
	{
		continue;
	}
	
	if ($type === T_OPEN_TAG) {
		if ($nextType === T_STRING && $nextValue === 'p') {
			$stream[] = [
				PI_OPEN_TAG,
				'<?p',
				$line
			];
			
			$i++;
			continue;
		} else {
			// todo it's regular PHP?
		}
	}
	
	$stream[] = [
		is_int($type) ? token_name($type): $type,
		$value,
		$line
	];
}

dump($stream);
