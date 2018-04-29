<?php

namespace app\models\user;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;


/**
 * Manage User Profile translations
 *
 * This class will create and update specific translations for a user profile.
 *
 * @package app\models\user
 */
class UserProfileLang extends UserProfileLangBase
{
	/**
	 * @param integer $userId
	 * @param array   $data
	 *
	 * @return array
	 */
	public static function createTranslation ( $userId, $data )
	{
		//  verify if language in data exists
		if ( !Lang::idExists(ArrayHelperEx::getValue($data, "lang_id")) ) {
			return self::buildError(self::ERR_LANG_NOT_FOUND);
		}

		$langId = ArrayHelperEx::getValue($data, "lang_id");

		if ( self::translationExists($userId, $langId)) {
			return self::buildError(self::ERR_TRANSLATION_EXISTS);
		}

		//  create translation with all attributes from data
		$model = new self();

		$model->lang_id   = $langId;
		$model->biography = ArrayHelperEx::getValue($data, "biography");
		$model->job_title = ArrayHelperEx::getValue($data, "job_title");

		//  if the model isn't valid, then return all errors
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		//  if the model couldn't be saved, then return an error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}

	/**
	 * @param integer $userId
	 * @param integer $langId
	 * @param array   $data
	 *
	 * @return array
	 */
	public static function updateTranslation ( $userId, $langId, $data )
	{
		//  if the translation doesn't exists, then return error
		if (!self::translationExists($userId, $langId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find the translation to update
		$model = self::find()->byUser($userId)->byLang($langId)->one();

		$model->biography = ArrayHelperEx::getValue($data, "biography", $model->biography);
		$model->job_title = ArrayHelperEx::getValue($data, "job_title", $model->job_title);

		//  if the model isn't valid, then return all errors
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		//  if the model couldn't be saved, then return an error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}
}
