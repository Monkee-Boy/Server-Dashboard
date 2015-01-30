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

	protected function convertBytes($bytes) {
		if($bytes >= 1099511627776)
		{
			return round($bytes/1099511627776,2).' TB';
		}
		elseif($bytes >= 1073741824)
		{
			return round($bytes/1073741824,2).' GB';
		}
		elseif($bytes >= 1048576)
		{
			return round($bytes/1048576,2).' MB';
		}
		elseif($bytes >= 1024)
		{
			return round($bytes/1024,2).' KB';
		}
		elseif($bytes > 0)
		{
			return $bytes.' B';
		}
		else
		{
			return '';
		}
	}

}
