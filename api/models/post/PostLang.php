<?php

namespace app\models\post;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;


/**
 * Class PostLang
 * @package app\models\post
 */
class PostLang extends PostLangBase
{
	/**
	 * TODO add comments
	 *
	 * @param integer $postId
	 * @param self    $data
	 *
	 * @return array
	 */
	public static function createTranslation ( $postId, $data )
	{
		//  if post doesn't exists, then throw an error
		if ( !Post::idExists($postId) ) {
			return self::buildError(self::ERR_POST_NOT_FOUND);
		}

		//  verify if language in data exists
		if ( !Lang::idExists(ArrayHelperEx::getValue($data, "lang_id")) ) {
			return self::buildError(self::ERR_LANG_NOT_FOUND);
		}

		//  create translation with all attributes from data
		$model = new self();

		$model->lang_id = ArrayHelperEx::getValue($data, "lang_id");
		$model->title   = ArrayHelperEx::getValue($data, "title");
		$model->slug    = ArrayHelperEx::getValue($data, "slug");
		$model->content = ArrayHelperEx::getValue($data, "content");

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
	 * TODO add comments
	 *
	 * @param integer $postId
	 *
	 * @return array
	 */
	public static function deleteTranslations ( $postId )
	{
		//  define result as success, will be overwritten by an error when necessary
		$result = self::buildSuccess([]);

		//  find all translations to be deleted
		$toDelete = self::find()->byPost($postId)->all();

		//  delete translations one by one to correctly trigger all events
		foreach ($toDelete as $translation) {
			if (!$translation->delete()) {
				$result = self::buildError(self::ERR_ON_DELETE);
				break;
			}
		}

		//  return the result
		return $result;
	}

	/**
	 * TODO add comments
	 *
	 * @param integer $postId
	 * @param integer $langId
	 * @param self    $data
	 *
	 * @return array
	 */
	public static function updateTranslation ( $postId, $langId, $data )
	{
		//  if the translation doesn't exists, then return error
		if (!self::translationExists($postId, $langId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find the translation to update
		$model = self::find()->byPost($postId)->byLang($langId)->one();

		$model->title   = ArrayHelperEx::getValue($data, "title", $model->title);
		$model->slug    = ArrayHelperEx::getValue($data, "slug", $model->slug);
		$model->content = ArrayHelperEx::getValue($data, "content", $model->content);

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