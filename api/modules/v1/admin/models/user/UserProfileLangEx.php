<?php

namespace app\modules\v1\admin\models\user;

use app\helpers\ArrayHelperEx;
use app\models\user\UserProfileLang;
use app\modules\v1\admin\models\LangEx;

/**
 * Class UserProfileLangEx
 *
 * @package app\modules\v1\admin\models\user
 */
class UserProfileLangEx extends UserProfileLang
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "UserProfileTranslation",
	 *
	 *     @SWG\Property( property = "language", ref = "#/definitions/Lang" ),
	 *     @SWG\Property( property = "job_title", type = "string" ),
	 *     @SWG\Property( property = "biography", type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"language" => "lang",
			"biography",
			"job_title",
		];
	}

	/**
	 * @inheritdoc
	 *            
	 * @SWG\Definition(
	 *       definition = "ProfileTranslationForm",
	 *
	 *     @SWG\Property( property = "lang_id",   type = "integer" ),
	 *     @SWG\Property( property = "biography", type = "string" ),
	 *     @SWG\Property( property = "job_title", type = "string" ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "lang_id", "required" ],
			[
				"lang_id", "exist",
				"targetClass"     => LangEx::className(),
				"targetAttribute" => [ "lang_id" => "id" ],
			],

			[ "biography", "string" ],
			[ "job_title", "string" ],
		];
	}

	/** @inheritdoc */
	public function getLang () { return $this->hasOne(LangEx::className(), [ "id" => "lang_id" ]); }

	/**
	 * @param integer $userId
	 * @param array   $translations
	 *
	 * @return array
	 */
	public static function manageTranslations ( $userId, $translations )
	{
		//  define result as success, it will be overwritten by an error when necessary
		$result = self::buildSuccess([]);

		foreach ($translations as $idx => $translation) {
			$lang = LangEx::find()->id(ArrayHelperEx::getValue($translation, "lang_id"))->one();

			//  verify if the language exists, return an error if it doesn't
			if (empty($lang) || is_null($lang)) {
				$result[ $idx ] = self::buildError(self::ERR_LANG_NOT_FOUND);
				continue;
			}

			//  if the translation exists, then update it, otherwise create it
			if (self::translationExists($userId, $lang->id)) {
				$result[ $lang->icu ] = self::updateTranslation($userId, $lang->id, $translation);
			} else {
				$result[ $lang->icu ] = self::createTranslation($userId, $translation);
			}
		}

		//  return result of each translation
		return $result;
	}
}
