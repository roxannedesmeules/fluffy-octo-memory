<?php

namespace app\modules\v1\admin\models;

use app\models\app\Lang;

/**
 * Class LangEx
 *
 * @package app\modules\v1\admin\models
 *
 * @SWG\Definition(
 *     definition = "Lang",
 *
 *     @SWG\Property( property = "id", type = "integer" ),
 *     @SWG\Property( property = "icu", type = "string" ),
 *     @SWG\Property( property = "name", type = "string" ),
 *     @SWG\Property( property = "native", type = "string" ),
 * )
 *
 * @SWG\Definition(
 *     definition = "Languages",
 *
 *     type = "array",
 *     @SWG\Items( ref = "#/definitions/Lang" )
 * )
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
