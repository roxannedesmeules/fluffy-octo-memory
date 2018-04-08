<?php
namespace app\modules\v1\admin\models\post;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;
use app\models\post\PostLang;
use app\modules\v1\admin\models\LangEx;

/**
 * Class PostLangEx
 *
 * @package app\modules\v1\admin\models\post
 */
class PostLangEx extends PostLang
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostTranslation",
	 *
	 *     @SWG\Property( property = "language", type = "string" ),
	 *     @SWG\Property( property = "title",    type = "string" ),
	 *     @SWG\Property( property = "slug",     type = "string" ),
	 *     @SWG\Property( property = "content",  type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"language" => function ( self $model ) { return $model->lang->icu; },
			"title",
			"slug",
			"content",
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostTranslationForm",
	 *       required   = { "lang_id", "title", "slug", "content" },
	 *
	 *     @SWG\Property( property = "tag_id",  type = "integer" ),
	 *     @SWG\Property( property = "lang_id", type = "integer" ),
	 *     @SWG\Property( property = "title",   type = "string" ),
	 *     @SWG\Property( property = "slug",    type = "string" ),
	 *     @SWG\Property( property = "content", type = "string" ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "post_id", "number" ],

			[ "lang_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[
				"lang_id", "exist",
				"targetClass"     => Lang::className(),
				"targetAttribute" => [ "lang_id" => "id" ],
				"message"         => self::ERR_FIELD_NOT_FOUND,
			],

			[ "title", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "title", "string", "max" => 255, "tooLong" => self::ERR_FIELD_TOO_LONG ],

			[ "slug", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "slug", "string", "max" => 255, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[
				"slug", "unique",
				"targetAttribute" => [ "slug", "lang_id" ],
				"message"         => self::ERR_FIELD_NOT_UNIQUE,
				"when"            => function ( self $model, string $attribute ) {
					$found = self::find()->byLang($model->lang_id)->where([ $attribute => $model->$attribute ])->one();

					return ($found) ? ($model->post_id !== $found->post_id) : true;
				},
			],

			[ "content", "string", "message" => self::ERR_FIELD_TYPE ],
		];
	}

	/**
	 * @param integer $postId
	 * @param self[]  $translations
	 *
	 * @return array
	 */
	public static function manageTranslations ( $postId, $translations )
	{
		//  if the post doesn't exists, then return an error
		if (!PostEx::idExists($postId)) {
			return self::buildError(self::ERR_POST_NOT_FOUND);
		}

		//  define result as success, it will be overwritten by an error when necessary
		$result = [];

		//  define which translations needs to be created and which ones needs to be updated
		foreach ($translations as $idx => $translation) {
			$langId = ArrayHelperEx::getValue($translation, "lang_id");

			//  verify if the language exists, return an error if it doesn't
			if (!LangEx::idExists($langId)) {
				$result[ $idx ] = self::buildError(self::ERR_LANG_NOT_FOUND);
				continue;
			}

			//  if the translation exists, then update it, otherwise create it
			if (self::translationExists($postId, $langId)) {
				$result[ $idx ] = self::updateTranslation($postId, $langId, $translation);
			} else {
				$result[ $idx ] = self::createTranslation($postId, $translation);
			}
		}

		//  return result of each translation
		return $result;
	}
}
