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
	protected $description = 'Loop through folders in /var/www to get size of domain and subdomains.';

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
		$path = '/var/www';

		// Clear stat cache to get fresh results
		clearstatcache();

		// Gather array of domains
		if(App::environment('local'))
		{
			$domains = [
				'defvayne23.com',
				'notbatman.com'
			];
		}
		elseif(App::environment('production'))
		{
			$domains = array_diff(scandir($path), array('..', '.', '.backups', '.logs'));
		}

		// Loop over domains to get subdomains
		foreach($domains as $domain)
		{
			$domain_path = $path.'/'.$domain;

			// Gather array of subdomains
			if(App::environment('local'))
			{
				$subdomains = [
					'_',
					'www'
				];
			}
			elseif(App::environment('production'))
			{
				if(is_dir($domain_path))
				{
					$subdomains = array_diff(scandir($domain_path), array('..', '.'));
				}
				else
				{
					// This is not a folder, and should not be here
					continue;
				}
			}

			// Loop over subdomains
			foreach($subdomains as $subdomain)
			{
				// Get first domain or create if doesn't exists
				$oDomain = Domain::firstOrCreate(array(
					'domain' => $domain,
					'subdomain' => $subdomain
				));

				$subdomain_path = $domain_path.'/'.$subdomain;

				// Loop inside directory to get total size and also current and shared folder sizes
				if(App::environment('local'))
				{
					$size = $this->loopDirectory($subdomain_path, 0);
				}
				else
				{
					if(is_dir($subdomain_path))
					{
						$size = $this->loopDirectory($subdomain_path, 0);
					}
					else
					{
						// This is not a folder, and should not be here
						continue;
					}
				}

				// Loop over size for total, current, shared
				foreach($size as $type=>$bytes)
				{
					$storage = Storage::firstOrCreate(array(
						'domain' => $oDomain->id,
						'type' => $type
					));

					$storage->size = $bytes;
					$storage->save();
				}
			}
		}
	}

	/**
	 * Loop over folders and files to get folder size recursivly.
	 *
	 * @return array
	 */
	protected function loopDirectory($path, $level = 0, $forceLinked = false)
	{
		if(App::environment('local'))
		{
			if($this->option('output'))
			{
				$this->info('Output option set...');
			}

			$size = [
				'total' => rand(8000,12000),
				'current' => rand(2000,5000),
				'releases' => rand(2000,5000),
				'shared' => rand(2000,5000)
			];
		}
		elseif(App::environment('production'))
		{
			if($level === 0)
			{
				$size = [
					'total' => 0,
					'current' => 0,
					'releases' => 0,
					'shared' => 0
				];

				$folders = array_diff(scandir($path), array('..', '.'));
				foreach($folders as $folder)
				{
					if($this->option('output'))
					{
						$this->info('('.$level.') Going into folder ... '.$path.'/'.$folder);
					}

					if($folder === 'current')
					{
						// Force loopDirectory to look into this linked directory
						$bytes = $this->loopDirectory($path.'/'.$folder, ++$level, true);
						$size['current'] += $bytes;
					}
					elseif($folder === 'shared')
					{
						$bytes = $this->loopDirectory($path.'/'.$folder, ++$level);
						$size['shared'] += $bytes;
						$size['total'] += $bytes;
					}
					elseif($folder === 'releases')
					{
						$bytes = $this->loopDirectory($path.'/'.$folder, ++$level);
						$size['releases'] += $bytes;
						$size['total'] += $bytes;
					}
					else
					{
						$bytes = $this->loopDirectory($path.'/'.$folder, ++$level);
						$size['total'] += $bytes;
					}
				}
			}
			else
			{
				// Sets size if folder may be empty
				$size = 0;

				if(!is_link($path) || $forceLinked === true)
				{
					if(is_link($path))
					{
						// Get symlink path because symlink itself throws off is_dir and filesize
						$original_path = $path;
						$path = readlink($path);
						if($this->option('output'))
						{
							$this->error('('.$level.') Changing path because original path is a symlink ... '.$original_path.' -> '.$path);
						}
					}

					if(is_dir($path))
					{
						$folders = array_diff(scandir($path), array('..', '.'));
						foreach($folders as $folder)
						{
							if($this->option('output'))
							{
								$this->info('('.$level.') Going into folder ... '.$path.'/'.$folder);
							}

							$size += $this->loopDirectory($path.'/'.$folder, ++$level);
						}
					}
					elseif(is_file($path))
					{
						$size = filesize($path);

						if($this->option('output'))
						{
							$this->info('('.$level.') Getting size of ... '.$path.' ('.$size.')');
						}
					}
					else
					{
						$size = 0;
						
						if($this->option('output'))
						{
							$this->error('('.$level.') This is neither directory or file ... '.$path);
						}
					}
				}
				else
				{
					// This is counted somewhere else and shouldn't be double counted
					$size = 0;

					if($this->option('output'))
					{
						$this->error('('.$level.') Skipping symlink file/folder ... '.$path);
					}
				}
			}
		}

		return $size;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('domain', InputArgument::OPTIONAL, 'Name of the domain to run', false),
			array('subdomain', InputArgument::OPTIONAL, 'Name of the subdomain to run', false),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('output', null, InputOption::VALUE_NONE, 'Flag if output should be displayed'),
		);
	}

}
