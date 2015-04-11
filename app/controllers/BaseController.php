<?php

class BaseController extends Controller {
	/**
	* The layout that should be used for responses.
	*/
	protected $layout = 'layouts.master';

	/**
	* Setup the layout used by the controller.
	*
	* @return void
	*/
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function convertBytes($bytes, $prefix = ' ', $suffix = '') {
		if($bytes >= 1099511627776)
		{
			return round($bytes/1099511627776,2).$prefix.'TB'.$suffix;
		}
		elseif($bytes >= 1073741824)
		{
			return round($bytes/1073741824,2).$prefix.'GB'.$suffix;
		}
		elseif($bytes >= 1048576)
		{
			return round($bytes/1048576,2).$prefix.'MB'.$suffix;
		}
		elseif($bytes >= 1024)
		{
			return round($bytes/1024,2).$prefix.'KB'.$suffix;
		}
		elseif($bytes > 0)
		{
			return $bytes.$prefix.'B'.$suffix;
		}
		else
		{
			return '';
		}
	}

}
