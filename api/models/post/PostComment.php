<?php

namespace app\models\post;

use app\helpers\ArrayHelperEx;

/**
 * Class PostComment
 *
 * @package app\models\post
 */
class PostComment extends PostCommentBase
{
	/**
	 * Create a single comment for a specific post translation, where the author is a guest from the blog. It will first
	 * verify that the post exists, then it will create the comment itself and validate that its content is valid before
	 * creating it.
	 *
	 * @param int $postId
	 * @param int $langId
	 * @param array $data
	 *
	 * @return array
	 */
	public static function createByVisitor ( $postId, $langId, $data )
	{
		//  check if the post exists
		if (!PostLang::translationExists($postId, $langId)) {
			return self::buildError(self::ERR_POST_NOT_FOUND);
		}

		//  create a model
		$model = new self();

		$model->post_id = $postId;
		$model->lang_id = $langId;
		$model->reply_comment_id = ArrayHelperEx::getValue($data, "reply_comment_id");

		$model->author  = ArrayHelperEx::getValue($data, "author");
		$model->email   = ArrayHelperEx::getValue($data, "email");
		$model->comment = ArrayHelperEx::getValue($data, "comment");

		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  if the model does save, return success
		return self::buildSuccess([]);
	}

	public static function createOne ( $postId, $langId, $data )
	{
		//  check if the post exists
		if (!PostLang::translationExists($postId, $langId)) {
			return self::buildError(self::ERR_POST_NOT_FOUND);
		}

		//  create a model
		$model = new self();

		$model->post_id = $postId;
		$model->lang_id = $langId;
		$model->reply_comment_id = ArrayHelperEx::getValue($data, "reply_comment_id");

		$model->user_id = ArrayHelperEx::getValue($data, "user_id");
		$model->author  = ArrayHelperEx::getValue($data, "author");
		$model->email   = ArrayHelperEx::getValue($data, "email");
		$model->comment = ArrayHelperEx::getValue($data, "comment");

		$model->is_approved = ArrayHelperEx::getValue($data, "is_approved", self::NOT_APPROVED);

		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  if the model does save, return success
		return self::buildSuccess([]);
	}

	public static function deleteOne ( $commentId )
	{
		//  if the comment doesn't exists, return an error
		if (!self::idExists($commentId)) {
			return self::buildError(self::ERR_COMMENT_NOT_FOUND);
		}

		//  find the model
		$model = self::find()->byId($commentId)->one();

		//  check if the comment has replies
		if ($model->hasReplies()) {
			return self::buildError(self::ERR_DELETE_HAS_REPLIES);
		}

		//  delete the comment
		if (!$model->delete()) {
			return self::buildError(self::ERR_ON_DELETE);
		}

		return self::buildSuccess([]);
	}

	public function hasReplies ()
	{
		return !empty($this->replies);
	}

	/**
	 * This method will update a single comment. It will first verify that the comment ID exists, then find the model
	 * and update it.
	 *
	 * @param int   $commentId
	 * @param array $data
	 *
	 * @return array
	 */
	public static function updateOne ( $commentId, $data )
	{
		//  if the comment doesn't exists, return an error
		if (!self::idExists($commentId)) {
			return self::buildError(self::ERR_COMMENT_NOT_FOUND);
		}

		//  find the model
		$model = self::find()->byId($commentId)->one();

		//  update the model attributes
		$model->comment     = ArrayHelperEx::getValue($data, "comment", $model->comment);
		$model->is_approved = ArrayHelperEx::getValue($data, "is_approved", $model->is_approved);

		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  if the model does save, return success
		return self::buildSuccess([]);
	}
}
