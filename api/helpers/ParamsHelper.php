<?php

namespace app\helpers;

/**
 * Class ParamsHelper
 *
 * @package app\helpers
 */
class ParamsHelper
{
	private function _getParams () { return \Yii::$app->params; }
	
	public function get ( $key )
	{
		return ArrayHelperEx::getValue(self::_getParams(), $key);
	}
}
