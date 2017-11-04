<?php
namespace app\helpers;

use yii\helpers\ArrayHelper;

/**
 * Class ArrayHelperEx
 *
 * @package app\helpers
 */
class ArrayHelperEx extends ArrayHelper
{
	const IN_QUOTES_SINGLE = 1;
	const IN_QUOTES_DOUBLE = 2;

	/**
	 * @param array $array
	 * @param int   $inQuotes
	 * @param bool  $isRecursive
	 *
	 * @return array|string
	 */
	public static function associativeArrayToString ( $array, $inQuotes = self::IN_QUOTES_DOUBLE, $isRecursive = false )
	{
		$result = array_map(function ( $key, $value ) use ( $inQuotes ) {
			if (is_array($value)) {
				$value = self::associativeArrayToString($value, $inQuotes, true);
			}

			switch ($inQuotes) {
				case self::IN_QUOTES_SINGLE :
					if (strpos($value, "[") !== 0) {
						return "'$key' => '$value'";
					} else {
						return "'$key' => $value";
					}

				case self::IN_QUOTES_DOUBLE :
					//  no break
				default :
					if (strpos($value, "[") !== 0) {
						return "\"$key\" => \"$value\"";
					} else {
						return "\"$key\" => $value";
					}
			}
		}, array_keys($array), array_values($array));

		if ($isRecursive) {
			$result = implode(", ", $result);
			return "[ $result ]";
		} else {
			return $result;
		}
	}

	/**
	 * Will return a list of elements where the index has a specific value.
	 *
	 * @example
	 * ```php
	 * $list = [
	 *      [ "id" => "1", "name" => "John", "role" => "admin" ],
	 *      [ "id" => "2", "name" => "Sansa", "role" => "admin" ],
	 *      [ "id" => "3", "name" => "Arya", "role" => "user" ],
	 *  ];
	 *
	 * $filtered = ArrayHelperEx::filterInArrayAtIndex("admin", $list, "role");
	 * #    should return :
	 * # [
	 *      [ "id" => "1", "name" => "John", "role" => "admin" ],
	 *      [ "id" => "2", "name" => "Sansa", "role" => "admin" ],
	 *  ];
	 * ```
	 *
	 * @param string|int|array $needle      single or list of values to search for
	 * @param array            $haystack    list in which the needle will be searched
	 * @param string|int       $index       at which index the value should be found
	 *
	 * @return array
	 */
	public static function filterInArrayAtIndex ( $needle, $haystack, $index )
	{
		return array_values(array_filter($haystack, function ($val) use ($needle, $index) {
			if (is_array($needle)) {
				return (in_array($val[$index], $needle));
			} else {
				return ($val[$index] == $needle);
			}
		}));
	}

	public static function getValue ( $array, $key, $default = null )
	{
		if ($key instanceof \Closure) {
			return $key($array, $default);
		}

		if (is_array($key)) {
			$lastKey = array_pop($key);
			foreach ($key as $keyPart) {
				$array = static::getValue($array, $keyPart);
			}
			$key = $lastKey;
		}

		if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array)) ) {
			return $array[$key];
		}

		if (($pos = strrpos($key, '.')) !== false) {
			$array = static::getValue($array, substr($key, 0, $pos), $default);
			$key = substr($key, $pos + 1);
		}

		if (is_object($array)) {
			return (isset($array->$key) && !is_null($array->$key)) ? $array->$key : $default;
		} elseif (is_array($array)) {
			return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
		} else {
			return $default;
		}
	}

	/**
	 * Verifies if the array passed in parameter has duplicate values.
	 *
	 * @param array $array
	 *
	 * @return bool
	 */
	public static function hasDuplicates (array $array)
	{
		return (count($array) !== count(array_flip($array)));
	}
}