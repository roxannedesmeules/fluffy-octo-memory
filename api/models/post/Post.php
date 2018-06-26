<?php

namespace app\models\post;

use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;
use app\models\app\Lang;
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
	 * This method will verify if a specific post can be published. It will make sure that there is a translation in
	 * every language available AND that each translation have the proper fields filled.
	 *
	 * @return array
	 */
	public function canBePublished ()
	{
		$error = [];

		//  Verify that the post has a translation for each language available
		$languages = Lang::find()->all();

		foreach ($languages as $lang) {
			$found = ArrayHelperEx::filterInArrayAtIndex($lang->id, $this->postLangs, "lang_id");

			if (empty($found)) {
				$error = self::ERR_MISSING_TRANSLATION;
			}
		}

		if (!empty($error)) {
			return self::buildError($error);
		}

		$error = [];

		//  Verify that all fields are filled
		foreach ($this->postLangs as $postLang) {
			$temp = $postLang->canBePublished();

			if ($temp[ "status" ] === PostLang::ERROR) {
				$error[ $postLang->lang->icu ] = $temp[ "error" ];
			}
		}

		if (!empty($error)) {
			return self::buildError($error);
		}

		return self::buildSuccess([]);
	}

	/**
	 * This method will create a single post entry. At first, verifications will be made to make sure the category and
	 * the post status exists, then the entry will be created.
	 *
	 * @param self $data
	 *
	 * @return array
	 */
	public static function createPost ( $data )
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
		$model->category_id        = ArrayHelperEx::getValue($data, "category_id");
		$model->post_status_id     = ArrayHelperEx::getValue($data, "post_status_id", PostStatus::DRAFT);
		$model->is_featured        = self::NOT_FEATURED;
		$model->is_comment_enabled = self::COMMENTS_ENABLED;

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
	public static function deletePost ( $postId )
	{
		//  check if the post id exists
		if (!self::idExists($postId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		if (self::isPublished($postId)) {
			return self::buildError(self::ERR_POST_PUBLISHED);
		}

		//  find the model to delete
		$model = self::find()->id($postId)->one();

		if ($model->hasComments()) {
			return self::buildError(self::ERR_POST_DELETE_COMMENTS);
		}

		//  delete model, in case of error, return it
		if ( !$model->delete() ) {
			return self::buildError(self::ERR_ON_DELETE);
		}

		//  return success
		return self::buildSuccess([]);
	}

	public function hasComments ()
	{
		return PostComment::find()->byPost($this->id)->exists();
	}

	/**
	 * Verify if the post associated to the postId passed in parameter is published.
	 *
	 * @param integer $postId
	 *
	 * @return bool
	 */
	public static function isPublished ( $postId )
	{
		if (!self::idExists($postId)) {
			return false;
		}

		$post = self::find()->id($postId)->one();

		return ($post->post_status_id == PostStatus::PUBLISHED);
	}

	/**
	 * Mark the post as updated, useful when one of it's translation is updated.
	 */
	public function markUpdated ()
	{
		$this->updated_on = date(DateHelper::DATETIME_FORMAT);
		$this->save();
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
	public static function updatePost ( $postId, $data )
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
		$model->category_id        = ArrayHelperEx::getValue($data, "category_id", $model->category_id);
		$model->post_status_id     = ArrayHelperEx::getValue($data, "post_status_id", $model->post_status_id);
		$model->is_featured        = ArrayHelperEx::getValue($data, "is_featured", $model->is_featured);
		$model->is_comment_enabled = ArrayHelperEx::getValue($data, "is_comment_enabled", $model->is_comment_enabled);

		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		if ($model->isAttributeChanged("post_status_id") && $model->post_status_id === PostStatus::PUBLISHED) {
			$post = Post::find()->id($postId)->one();

			$result = $post->canBePublished();

			if ($result[ "status" ] === Post::ERROR) {
				return $result;
			}
		}

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}
}