<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('validate_date'))
{
	function validate_date($date, $format = 'Y/m/d')
	{
		log_message('debug', 'validateDate $date : '.($date));
		if (is_null($date) || strlen($date) <= 0) {
			return true;
		}
				
		$d = DateTime::createFromFormat($format, $date);
		log_message('debug', 'validateDate $d : '.json_encode($d));
		return $d && $d->format($format) == $date;
	}
}