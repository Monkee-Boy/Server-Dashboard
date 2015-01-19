<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DomainBandwidth extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dashboard:bandwidth';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Read the access log for Apache to gather details of all requests. Main goal is to gather data sent/received per domain, but other data can be analysed as well.';

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
		/**
		 * Apache expected log format.
		 * %a  %D  \"%f\" %{Host}i  \"%{Referer}i\"  \"%{User-Agent}i\" %I  %m  %O  \"%r\"  %>s  \"%{%F %T}t\"  \"%U\"
		 *
		 * Log definitions.
		 *   %a	- Remote IP-address
		 *   %D	- The time taken to serve the request, in microseconds.
		 *   %f	- Filename
		 *   %H	- The request protocol
		 *   %{Foobar}i	- The contents of Foobar: header line(s) in the request sent to the server. Changes made by other modules (e.g. mod_headers) affect this. If you're interested in what the request header was prior to when most modules would have modified it, use mod_setenvif to copy the header into an internal environment variable and log that value with the %{VARNAME}e described above.
		 *   %I - Bytes received, including request and headers, cannot be zero.
		 *   %l -	Remote logname (from identd, if supplied). This will return a dash unless mod_ident is present and IdentityCheck is set On.
		 *   %m	- The request method
		 *   %O - Bytes sent, including headers, cannot be zero.
		 *   %r	- First line of request
		 *   %s	- Status. For requests that got internally redirected, this is the status of the *original* request --- %>s for the last.
		 *   %t	- Time the request was received (standard english format)
		 *   %U	- The URL path requested, not including any query string.
		 */

		if(App::environment('local'))
		{
			$sData = "70.112.137.185  643  \"/var/www/notbatman.com/_/public_html/css/style.css\"  notbatman.com  \"http://notbatman.com/\"  \"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36\"  666  GET  2765  200  \"2015-01-18 21:00:58\"  \"/css/style.css\"\n";

			$sData .= "70.112.137.185  283  \"/var/www/notbatman.com/_/public_html/js/common.js\"  notbatman.com  \"http://notbatman.com/\"  \"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36\"  639  GET  142  304  \"2015-01-18 21:00:58\"  \"/js/common.js\"\n";

			$sData .= "70.112.137.185  326  \"/var/www/notbatman.com/_/public_html/js/plugins.js\"  notbatman.com  \"http://notbatman.com/\"  \"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36\"  646  GET  810  200  \"2015-01-18 21:00:58\"  \"/js/plugins.js\"\n";

			$sData .= "70.112.137.185  835  \"/var/www/notbatman.com/_/public_html/js/modernizr-2.0.6.min.js\"  something.another.notbatman.com  \"http://notbatman.com/\"  \"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36\"  659  GET  7001  200  \"2015-01-18 21:00:58\"  \"/js/modernizr-2.0.6.min.js\"\n";

			$sData .= "70.112.137.185  4669  \"/var/www/notbatman.com/_/public_html/js/jquery-1.7.1.min.js\"  notbatman.com  \"http://notbatman.com/\"  \"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36\"  657  GET  33448  200  \"2015-01-18 21:00:58\"  \"/js/jquery-1.7.1.min.js\"\n";

			$sData .= "70.112.137.185  371  \"/var/www/notbatman.com/_/public_html/humans.txt\"  test.notbatman.com  \"-\"  \"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36\"  494  GET  465   404  \"2015-01-18 21:00:58\"  \"/humans.txt\"";
		}
		elseif(App::environment('production'))
		{
			$sData = file_get_contents("/var/www/.logs/access.log"); // Get the access.log file.
		}

		$aKeys = array("ip", "time_taken", "file_name", "host", "referer", "user_agent", "bytes_received", "request_method", "bytes_sent", "status", "timestamp", "url");

		$aData = explode("\n", $sData);
		$sInserted = 0;

		foreach($aData as $sRow)
		{
			$aRow = explode("  ", $sRow);
			$aRow = $this->array_fill_keys($aKeys, $aRow);

			// Data cleanup
			$aRow['file_name'] = substr($aRow['file_name'], 1, -1);
			$aRow['referer'] = substr($aRow['referer'], 1, -1);
			$aRow['user_agent'] = substr($aRow['user_agent'], 1, -1);
			$aRow['timestamp'] = substr($aRow['timestamp'], 1, -1);
			$aRow['url'] = substr($aRow['url'], 1, -1);

			// Build domain and subdomain
			if($aRow['host'] === '104.237.135.230')
			{
				$sDomain = "104.237.135.230";
				$sSubDomain = "_";
			}
			else
			{
				$aDomain = explode('.', $aRow['host']);
				$aDomain = array_reverse($aDomain);
				$sDomain = $aDomain[1].'.'.$aDomain[0];

				if(count($aDomain) > 2)
				{
					$sSubDomain = $aDomain[2];
					for($i=3;$i<count($aDomain);$i++)
					{
						$sSubDomain = $aDomain[$i].".".$sSubDomain;
					}
				}
				else
				{
					$sSubDomain = "_";
				}
			}

			// Find domain id, if not create it
			$domain = Domain::firstOrCreate(array(
				'domain' => $sDomain,
				'subdomain' => $sSubDomain
			));

			if(App::environment('local'))
			{
				// Show data on local for debugging
				echo "\n";
				echo $domain->id."\n";
				echo $sDomain."\n";
				echo $sSubDomain."\n";
				print_r($aRow);
				echo "\n\n";
			}

			// Now insert data into bandwidth table
			Bandwidth::create(array(
				'domain' => $domain->id,
				'ip' => $aRow['ip'],
				'request_time' => $aRow['timestamp'],
				'time_taken' => $aRow['time_taken'],
				'method' => $aRow['request_method'],
				'status' => $aRow['status'],
				'bytes_received' => $aRow['bytes_received'],
				'bytes_sent' => $aRow['bytes_sent'],
				'referer' => $aRow['referer'],
				'user_agent' => $aRow['user_agent'],
				'file_name' => $aRow['file_name'],
				'url' => $aRow['url']
			));

			$sInserted++;
		}

		echo $sInserted." rows of data have been imported to track bandwidth.\n";
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

	/**
	 * Attach keys to an array.
	 *
	 * @param array $keys
	 * @param mixed $values
	 * @return array
	 */
	protected function array_fill_keys($target, $value = '')
	{
		if(is_array($target))
		{
			foreach($target as $key => $val)
			{
				$filledArray[$val] = is_array($value) ? $value[$key] : $value;
			}
		}
		return $filledArray;
	}

}
