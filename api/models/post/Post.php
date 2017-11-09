<?php

namespace app\models\post;
use app\helpers\ArrayHelperEx;
use app\models\category\Category;


/**
 * Class Post
 * Manage blog posts
 *
 * @package app\models\post
 */
class Post extends PostBase
{
	/**
	 * This method will create a single post entry. At first, verifications will be made to make sure the category and
	 * the post status exists, then the entry will be created.
	 *
	 * @param self $data
	 *
	 * @return array
	 */
	public function createPost ( $data )
	{
		//  make sure category id exists
		if (!Category::idExists(ArrayHelperEx::getValue($data, "category_id"))) {
			return self::buildError(self::ERR_CATEGORY_NOT_FOUND);
		}

		//  make sure post status id exists
		if (!PostStatus::idExists(ArrayHelperEx::getValue($data, "post_status_id")) &&
			!is_null(ArrayHelperEx::getValue($data, "post_status_id"))) {
			return self::buildError(self::ERR_STATUS_NOT_FOUND);
		}

		//  create a model
		$model = new self();

		//  assign all attributes
		$model->category_id    = ArrayHelperEx::getValue($data, "category_id");
		$model->post_status_id = ArrayHelperEx::getValue($data, "post_status_id", PostStatus::DRAFT);

		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return the post_id
		return self::buildSuccess([ "post_id" => $model->id ]);
	}

	/**
	 * This method will delete a single post entry. It will verify that the post ID exists before deleting it.
	 *
	 * @see PostLang::deleteTranslations()  - call this method to delete translations and avoid any error.
	 *
	 * @param integer $postId
	 *
	 * @return array
	 */
	public function deletePost ( $postId )
	{
		//  check if the post id exists
		if (!self::idExists($postId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find the model to delete
		$model = self::find()->id($postId)->one();

		//  delete model, in case of error, return it
		if ( !$model->delete() ) {
			return self::buildError(self::ERR_ON_DELETE);
		}

		//  return success
		return self::buildSuccess([]);
	}

	/**
	 * This method will update the post entry. It will first make sure the post ID exists, then verify that the category
	 * ID and post status ID also exists in database, then the entry will be updated.
	 *
	 * @param integer $postId
	 * @param self    $data
	 *
	 * @return array
	 */
	public function updatePost ( $postId, $data )
	{
		//  check if the post id exists
		if (!self::idExists($postId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  make sure category id exists
		if (!Category::idExists(ArrayHelperEx::getValue($data, "category_id"))) {
			return self::buildError(self::ERR_CATEGORY_NOT_FOUND);
		}

		//  make sure post status id exists
		if (!PostStatus::idExists(ArrayHelperEx::getValue($data, "post_status_id"))) {
			return self::buildError(self::ERR_STATUS_NOT_FOUND);
		}

		//  find the model to update
		$model = self::find()->id($postId)->one();

		//  assign all attributes
		$model->category_id    = ArrayHelperEx::getValue($data, "category_id", $model->category_id);
		$model->post_status_id = ArrayHelperEx::getValue($data, "post_status_id", $model->post_status_id);

		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}
}