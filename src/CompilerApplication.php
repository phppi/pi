<?php declare(strict_types = 1);

namespace Pi;

class CompilerApplication
{
	/** @var \Symfony\Component\Console\Application */
	private $app;
	
	/** @var  */
	private $di;
	
	
	public function __construct(\Symfony\Component\Console\Application $app)
	{
		$this->app = $app;
		
		$this->app->addCommands();
	}
}
