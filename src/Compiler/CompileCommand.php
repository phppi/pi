<?php declare(strict_types = 1);

namespace PiCompiler\Compiler;

use Nette\Utils\Finder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompileCommand extends Command
{
	public const COMMAND_NAME = 'compile';
	private const ARG_FILES = 'file';
	private const REQUIRED_ARRAY = InputArgument::IS_ARRAY | InputArgument::REQUIRED;
	/** @var \PiCompiler\Compiler\Configuration */
	private $configuration;
	private $files;
	
	/**
	 *
	 * @throws \Symfony\Component\Console\Exception\LogicException
	 */
	public function __construct(\PiCompiler\Compiler\Configuration $configuration)
	{
		parent::__construct(self::COMMAND_NAME);
		
		$this->configuration = $configuration;
		
		$this->addArgument(self::ARG_FILES, self::REQUIRED_ARRAY, '', []);
		$this->setDescription('Compile files');
	}
	
	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		parent::initialize($input, $output);
		
		$this->files = $input->getArgument(self::ARG_FILES);
		
		if ($this->files === []) {
			// TODO hezkou message, kdyz chybi files
			die('Missing file.');
		}
	}
	
	public function execute(InputInterface $input, OutputInterface $output)
	{
		
		\dump('-------------');

//		dump($this->configuration);
//		dump($this->configuration->getDefaultPreset());
		
		Finder::findFiles('*.phpp')
	
	
	}
	
	public function getFiles(): array
	{
		return $this->files;
	}
}
