<?php
namespace app\modules\v1\admin\models\category;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;
use app\models\category\CategoryLang;

/**
 * Class CategoryLangEx
 * 
 * @package app\modules\v1\admin\models\category
 */
class CategoryLangEx extends CategoryLang
{

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "CategoryTranslation",
	 *
	 *     @SWG\Property( property = "language", type = "string" ),
	 *     @SWG\Property( property = "name", type = "string" ),
	 *     @SWG\Property( property = "slug", type = "string" ),
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
	 *       definition = "CategoryTranslationForm",
	 *       required   = { "lang_id", "name", "slug" },
	 *
	 *     @SWG\Property( property = "lang_id", type = "integer" ),
	 *     @SWG\Property( property = "name", type = "string" ),
	 *     @SWG\Property( property = "slug", type = "string" ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "lang_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "lang_id", "exist", 'targetClass' => Lang::className(), "targetAttribute" => [ "lang_id" => "id" ], "message" => self::ERR_FIELD_NOT_FOUND ],
			
			[ "name", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "name", "string", "max" => 255, "message" => self::ERR_FIELD_TYPE, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[ "name", "unique", "targetAttribute" => [ "name", "lang_id" ], "message" => self::ERR_FIELD_NOT_UNIQUE ],
			
			[ "slug", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "slug", "string", "max" => 255, "message" => self::ERR_FIELD_TYPE, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[ "slug", "unique", "targetAttribute" => [ "slug", "lang_id" ], "message" => self::ERR_FIELD_NOT_UNIQUE ],
		];
	}
	
	/**
	 * @param int   $categoryId
	 * @param array $translations
	 *
	 * @return array
	 */
	public static function manageTranslations ( $categoryId, $translations )
	{
		//  if category doesn't exists, then throw an error
		if ( !CategoryEx::idExists($categoryId) ) {
			return self::buildError(self::ERR_CATEGORY_NOT_FOUND);
		}

		//  define result as success, will be overwritten by an error when necessary
		$result = self::buildSuccess([]);

		//  for each possible translation, define if it needs to be created or updated
		foreach ( $translations as $translation ) {
			$langId = ArrayHelperEx::getValue($translation, "lang_id");

			//  verify if language in data exists
			if ( !Lang::idExists($langId) ) {
				$result = self::buildError(self::ERR_LANG_NOT_FOUND);
				break;
			}

			//  if the translation exists, then update it, otherwise create it
			if ( self::translationExists($categoryId, $langId) ) {
				$result = self::updateTranslation($categoryId, $langId, $translation);
			} else {
				$result = self::createTranslation($categoryId, $translation);
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