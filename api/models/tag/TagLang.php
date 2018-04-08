<?php

namespace app\models\tag;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;


/**
 * Class TagLang
 * @package app\models\tag
 */
class TagLang extends TagLangBase
{
	public static function createTranslation ( $tagId, $data )
	{
		//  verify if tag ID is valid
		if (!Tag::idExists($tagId)) {
			return self::buildError(self::ERR_TAG_NOT_FOUND);
		}

		//  verify if lang ID is valid
		if (!Lang::idExists(ArrayHelperEx::getValue($data, "lang_id"))) {
			return self::buildError(self::ERR_LANG_NOT_FOUND);
		}

		$langId = ArrayHelperEx::getValue($data, "lang_id");

		//  verify if translation doesn't exists
		if (self::translationExists($tagId, $langId)) {
			return self::buildError(self::ERR_TRANSLATION_EXISTS);
		}

		//  build new model
		$model = new self();

		$model->tag_id  = (int) $tagId;
		$model->lang_id = (int) $langId;
		$model->name    = ArrayHelperEx::getValue($data, "name");
		$model->slug    = ArrayHelperEx::getValue($data, "slug");

		//  if the model isn't valid, then return all errors
		if (!$model->validate()) {
			return self::buildError($model->getErrors());
		}

		//  if the model couldn't be saved, then return an error
		if (!$model->save()) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}

	public static function deleteTranslations ( $tagId )
	{
		//  define the result as success, will be overwritten by an error when necessary
		$result = self::buildSuccess([]);

		//  find all translations to be deleted
		$toDelete = self::find()->byTag($tagId)->all();

		//  delete translations one by one to correctly trigger all events
		foreach ($toDelete as $translation) {
			if (!$translation->delete()) {
				$result = self::buildError(self::ERR_ON_DELETE);
				break;
			}
		}

		// return the result
		return $result;
	}

	public static function updateTranslation ( $tagId, $langId, $data )
	{
		//  verify if translation doesn't exists
		if (!self::translationExists($tagId, $langId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find the translation to update
		$model = self::find()->byTag($tagId)->byLang($langId)->one();

		$model->name = ArrayHelperEx::getValue($data, "name", $model->name);
		$model->slug = ArrayHelperEx::getValue($data, "slug", $model->slug);

		//  if the model isn't valid, return all errors
		if (!$model->validate()) {
			return self::buildError($model->getErrors());
		}

		//  if the model couldn't be saved, then return an error
		if (!$model->save()) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}
}