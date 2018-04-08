<?php

namespace app\modules\v1\admin\models\tag;

use app\helpers\ArrayHelperEx;
use app\models\tag\TagLang;
use app\modules\v1\admin\models\LangEx;

/**
 * Class TagLangEx
 *
 * @package app\modules\v1\admin\tag
 */
class TagLangEx extends TagLang
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "TagTranslation",
	 *
	 *     @SWG\Property( property = "language", type = "string" ),
	 *     @SWG\Property( property = "name",     type = "string" ),
	 *     @SWG\Property( property = "slug",     type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"language" => function ( self $model ) { return $model->lang->icu; },
			"name",
			"slug",
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "TagTranslationForm",
	 *       required   = { "lang_id", "name", "slug" },
	 *
	 *     @SWG\Property( property = "tag_id",  type = "integer" ),
	 *     @SWG\Property( property = "lang_id", type = "integer" ),
	 *     @SWG\Property( property = "name",    type = "string" ),
	 *     @SWG\Property( property = "slug",    type = "string" ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "tag_id", "number" ],

			[ "lang_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "lang_id", "exist", "targetClass" => LangEx::className(), "targetAttribute" => [ "lang_id" => "id" ], "message" => self::ERR_FIELD_NOT_FOUND ],

			[ "name", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "name", "string", "max" => 255, "message" => self::ERR_FIELD_TYPE, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[
				"name", "unique",
				"targetAttribute" => [ "name", "lang_id" ],
				"message"         => self::ERR_FIELD_NOT_UNIQUE,
				"when"            => function ( self $model, string $attribute ) {
					$found = self::find()->byLang($model->lang_id)->where([ $attribute => $model->$attribute ])->one();

					return ($found) ? ($model->tag_id !== $found->tag_id) : true;
				},
			],

			[ "slug", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "slug", "string", "max" => 255, "message" => self::ERR_FIELD_TYPE, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[
				"slug", "unique",
				"targetAttribute" => [ "slug", "lang_id" ],
				"message"         => self::ERR_FIELD_NOT_UNIQUE,
				"when"            => function ( self $model, string $attribute ) {
					$found = self::find()->byLang($model->lang_id)->where([ $attribute => $model->$attribute ])->one();

					return ($found) ? ($model->tag_id !== $found->tag_id) : true;
				},
			],
		];
	}

	/**
	 * @param $tagId
	 * @param $translations
	 *
	 * @return array
	 */
	public static function manageTranslations ( $tagId, $translations )
	{
		//  if the tag doesn't exists, then throw an error
		if ( !TagEx::idExists($tagId) ) {
			return self::buildError(self::ERR_TAG_NOT_FOUND);
		}

		//  define the result as success, it will be overwritten by an error when necessary
		$result = self::buildSuccess([]);

		//  for each possible translations, define if it needs to be created or updated
		foreach ( $translations as $translation ) {
			$langId = ArrayHelperEx::getValue($translation, "lang_id");

			//  verify if language in data exists
			if ( !LangEx::idExists($langId) ) {
				$result = self::buildError(self::ERR_LANG_NOT_FOUND);
				break;
			}

			//  if the translation exists, then update it, otherwise create it
			if ( self::translationExists($tagId, $langId) ) {
				$result = self::updateTranslation($tagId, $langId, $translation);
			} else {
				$result = self::createTranslation($tagId, $translation);
			}

			//  if there was an error, then stop here
			if ( $result[ "status" ] === self::ERROR ) {
				break;
			}
		}

		//  return the result
		return $result;
	}
}
