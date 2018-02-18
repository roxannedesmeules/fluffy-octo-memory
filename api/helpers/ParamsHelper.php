<?php

namespace app\helpers;

/**
 * Class ParamsHelper
 *
 * @package app\helpers
 */
class ParamsHelper
{
	private static function _getParams () { return \Yii::$app->params; }
	
	public static function get ( $key )
	{
		return ArrayHelperEx::getValue(self::_getParams(), $key);
	}
}
