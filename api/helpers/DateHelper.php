<?php

namespace app\helpers;

/**
 * Class DateHelper
 *
 * @package app\helpers
 */
class DateHelper
{
	const DATETIME_FORMAT = "Y-m-d H:i:s";

	const DATE_FORMAT = "Y-m-d";

	/**
	 * This function will format the string accordingly to the format passed in parameter. Before formatting the date, the
	 * function will verify if the date is empty and if it is the case, then it will return an empty string.
	 *
	 * @param string $date          date to format
	 * @param string $format        format in which the date should be formatted
	 * @param string $emptyStr      string to return when date is empty
	 *
	 * @return false|string
	 */
	public static function formatDate ( $date, $format = self::DATE_FORMAT, $emptyStr = "" )
	{
		//  return empty string if date is empty
		if (self::isEmpty($date))
			return $emptyStr;

		//  format the date
		return ( date($format, strtotime($date)) );
	}

	/**
	 * This function will verify if the date passed in param is empty, null or has the default value from MySQL.
	 *
	 * @param $date
	 *
	 * @return bool
	 */
	public static function isEmpty ( $date )
	{
		//  if date is empty, then return true.
		if (empty($date))
			return true;

		//  if date is null, then return true.
		if (is_null($date))
			return true;

		//  if date is set to zeros, then return true.
		if ($date == "0000-00-00" || $date == "0000-00-00 00:00:00")
			return true;

		//  if here, then date is
		return false;
	}
}
