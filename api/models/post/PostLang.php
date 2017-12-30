<?php

namespace app\models\post;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;
use yii\db\Exception;


/**
 * Class PostLang
 * Manage Post translations
 *
 * @package app\models\post
 */
class PostLang extends PostLangBase
{
	/**
	 * This method will create a single translation for a specific post. First, we will make sure the post itself exists,
	 * then verify that the language is valid and create the translation.
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

		$langId = ArrayHelperEx::getValue($data, "lang_id");

		if ( self::translationExists($postId, $langId)) {
			return self::buildError(self::ERR_TRANSLATION_EXISTS);
		}

		//  create translation with all attributes from data
		$model = new self();

		$model->post_id = $postId;
		$model->lang_id = $langId;
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
	 * This method will delete all translation for a specific post. Translations will be deleted one by one to make sure
	 * that any events that should be triggered are triggered.
	 *
	 * @param integer $postId
	 *
	 * @return array
	 *
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public static function deleteTranslations ( $postId )
	{
		//  make sure to not delete translations of a published post
		if (Post::isPublished($postId)) {
			return self::buildError(self::ERR_POST_PUBLISHED);
		}

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
	 * This method will update a single post translation. It will first make sure the translation exists, then will find
	 * the entry and update it.
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