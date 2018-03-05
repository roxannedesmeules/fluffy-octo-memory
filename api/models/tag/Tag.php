<?php

namespace app\models\tag;


/**
 * Class Tag
 *
 * @package app\models\tag
 */
class Tag extends TagBase
{
	public static function createTag ( )
	{
		//  create a model
		$model = new self();

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return the category_id
		return self::buildSuccess([ "tag_id" => $model->id ]);
	}

	public static function deleteTag ( $tagId )
	{
		//  check if the tag ID exists
		if ( !self::idExists($tagId) ) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find the model to delete
		$model = self::find()->id($tagId)->one();

		//  if model is linked to published posts, then return an error
		if ($model->hasPublishedPosts()) {
			return self::buildError(self::ERR_DELETE_POSTS);
		}

		//  if model still has translations, then return an error, translations should be deleted first
		if ($model->hasTranslations()) {
			return self::buildError(self::ERR_HAS_TRANSLATIONS);
		}

		//  delete model, in case of error, return it
		if ( !$model->delete() ) {
			return self::buildError(self::ERR_ON_DELETE);
		}

		//  return success
		return self::buildSuccess([]);
	}

	public static function updateTag ( $tagId )
	{
		//  check if the tag ID exists
		if ( !self::idExists($tagId) ) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find the model to update
		$model = self::find()->id($tagId)->one();

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}
}