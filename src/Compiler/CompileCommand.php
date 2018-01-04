<?php declare(strict_types = 1);

namespace PiCompiler\Compiler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompileCommand extends Command
{
	private const ARG_FILES = 'files';
	/** @var \PiCompiler\Compiler\Configuration */
	private $configuration;
	
	private $files;
	
	public function __construct(\PiCompiler\Compiler\Configuration $configuration)
	{
		$this->setName('compile');
		$this->setDescription('Compile files');
		$this->addArgument(self::ARG_FILES, InputArgument::IS_ARRAY, 'Files to compile');
		
		parent::__construct();
		
		$this->configuration = $configuration;
		
		
	}
	
	
	public function run(InputInterface $input, OutputInterface $output)
	{
		
		\dump('-------------');
		
		dump($this->configuration);
		dump($this->configuration->getDefaultPreset());
		
		\dump($input);
		\dump($input->getArgument(self::ARG_FILES));
	}
}
