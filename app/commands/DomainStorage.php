<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DomainStorage extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dashboard:storage';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Loop through folders in /var/www to get size of domain and subdomains. Also store numbers of files.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
