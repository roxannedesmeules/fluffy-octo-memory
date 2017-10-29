<?php

namespace app\modules\v1\admin\models;

use app\models\app\Lang;

/**
 * Class LangEx
 *
 * @package app\modules\v1\admin\models
 */
class LangEx extends Lang
{
	/** @inheritdoc */
	public function fields ()
	{
		return [ "id", "icu", "name", "native", ];
	}

	/**
	 * @return Lang[]|array
	 */
	public static function getAllLanguages ()
	{
		return self::find()->asArray()->all();
	}
}
