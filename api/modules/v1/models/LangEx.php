<?php

namespace app\modules\v1\models;

use app\models\app\Lang;

/**
 * Class LangEx
 *
 * @package app\modules\v1\models
 */
class LangEx extends Lang
{
	/** @inheritdoc */
	public function fields ()
	{
		return [ "id", "icu", "name", "native", ];
	}

	/**
	 * Find the Language ID from a given ICU.
	 *
	 * @param string $langIcu
	 *
	 * @return int
	 */
	public static function getIdFromIcu ( $langIcu )
	{
		$lang = self::find()->icu($langIcu)->one();

		if (is_null($lang)) {
			return 0;
		}

		return $lang->id;
	}
}
