<?php

namespace app\modules\v1\admin\models\tag;

use app\models\tag\TagLang;
use app\modules\v1\admin\models\LangEx;

/**
 * Class TagLangEx
 *
 * @package app\modules\v1\admin\tag
 */
class TagLangEx extends TagLang
{
	public function rules ()
	{
		return [
			[ "lang_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "lang_id", "exist", "targetClass" => LangEx::className(), "targetAttribute" => [ "lang_id" => "id" ], "message" => self::ERR_FIELD_NOT_FOUND ],

			[ "name", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "name", "string", "max" => 255, "message" => self::ERR_FIELD_TYPE, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[
				"name", "unique",
				"targetAttribute" => [ "name", "lang_id" ],
				"message"         => self::ERR_FIELD_NOT_UNIQUE,
				"when"            => function ( self $model ) { return $model->isAttributeChanged("name"); },
			],

			[ "slug", "string", "max" => 255, "message" => self::ERR_FIELD_TYPE, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[
				"slug", "unique",
				"targetAttribute" => [ "slug", "lang_id" ],
				"message"         => self::ERR_FIELD_NOT_UNIQUE,
				"when"            => function ( self $model ) { return $model->isAttributeChanged("slug"); },
			],
		];
	}
}
