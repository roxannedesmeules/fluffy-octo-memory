<?php

namespace app\modules\v1\admin\models\tag;

use app\components\validators\ArrayUniqueValidator;
use app\components\validators\TranslationValidator;
use app\models\tag\Tag;

/**
 * Class TagEx
 *
 * @package app\modules\v1\admin\models\tag
 *
 * @property array $translations
 */
class TagEx extends Tag
{
	const ERR_FIELD_REQUIRED    = "ERR_FIELD_REQUIRED";
	const ERR_FIELD_UNIQUE_LANG = "ERR_FIELD_UNIQUE_LANG";

	/** @var array  */
	public $translations = [];

	/**
	 * @inheritdoc
	 */
	public function rules ()
	{
		return [
			[ "translations", "required", "strict" => true, "message" => self::ERR_FIELD_REQUIRED ],
			[ "translations", TranslationValidator::className(), "validator" => TagLangEx::className() ],
			[ "translations", ArrayUniqueValidator::className(), "uniqueKey" => "lang_id", "message" => self::ERR_FIELD_UNIQUE_LANG],
		];
	}
}
