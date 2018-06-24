<?php

namespace app\modules\v1\admin\controllers\post;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\post\PostCommentEx;
use app\modules\v1\admin\models\post\PostEx;

/**
 * Class CommentController
 *
 * @package app\modules\v1\admin\controllers\post
 */
class CommentController extends ControllerAdminEx
{
	/**
	 * Get all comments linked to a specific post, no mather the language.
	 *
	 * @param int $postId
	 *
	 * @return array|mixed
	 *
	 * @SWG\Get(
	 *     path = "/posts/:postId/comments",
	 *     tags = { "Posts", "Post comments" },
	 *     summary = "Get all comments linked to a post",
	 *     description = "Get the comment tree linked to a specific post grouped by language.",
	 *
	 *     @SWG\Parameter( name = "postId", in = "path", type = "integer", required = true, description = "Post ID for which the comments needs to be fetch." ),
	 *
	 *     @SWG\Response( response = 200, description = "List of comments", ),
	 *     @SWG\Response( response = 401, description = "Invalid credentials", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "Post not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex ( $postId )
	{
		if (!PostEx::idExists($postId)) {
			return $this->error(404, "Post not found");
		}

		return PostCommentEx::getCommentsForPost($postId);
	}

	/**
	 * @param $postId
	 *
	 * @return array|mixed
	 * @throws \yii\base\InvalidConfigException
	 *
	 * @SWG\Post(
	 *     path = "/posts/:postId/comments",
	 *     tags = { "Posts", "Post comments" },
	 *     summary = "Create a comment",
	 *     description = "Create a comment for a specific post and mark the author as the authenticated user",
	 *
	 *     @SWG\Parameter( name = "postId", in = "path", type = "integer", required = true, description = "Post ID for which the comment needs to be created" ),
	 *
	 *     @SWG\Response( response = 200, description = "List of comments", ),
	 *     @SWG\Response( response = 400, description = "An error occurred", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 401, description = "Invalid credentials", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "Post not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "Comment couldn't be created", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 * )
	 */
	public function actionCreate ( $postId )
	{
		if (!PostEx::idExists($postId)) {
			return $this->error(404, "Post not found");
		}

		$form = new PostCommentEx();

		$form->setAttributes($this->request->getBodyParams());
		$form->post_id = $postId;

		if (!$form->validate()) {
			return $this->error(422, $form->getErrors());
		}

		$result = PostCommentEx::createByUser($postId, $form);

		if ($result[ "status" ] === PostCommentEx::ERROR) {
			$this->error(400, $result[ "error" ]);
		}

		return PostCommentEx::getCommentsForPost($postId);
	}

	/**
	 * @param int $postId   - post ID to which the comment is linked
	 * @param int $id       - comment ID to update
	 *
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 *
	 * @SWG\Put(
	 *     path = "/posts/:postId/comments/:id",
	 *     tags = { "Posts", "Post comments" },
	 *     summary = "Update a single comment",
	 *     description = "Update the attributes of a specific comment.",
	 *
	 *     @SWG\Parameter( name = "postId", in = "path", type = "integer", required = true ),
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *
	 *     @SWG\Response( response = 200, description = "List of comments", ),
	 *     @SWG\Response( response = 400, description = "An error occurred", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 401, description = "Invalid credentials", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "Post not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "Comment couldn't be created", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 * )
	 */
	public function actionUpdate ( $postId, $id )
	{
		if (!PostEx::idExists($postId)) {
			return $this->error(404, "Post not found");
		}

		if (!PostCommentEx::idExists($id)) {
			return $this->error(404, "Comment not found");
		}

		$result = PostCommentEx::updateOne($id, $this->request->getBodyParams());

		if ($result[ "status" ] === PostCommentEx::ERROR) {
			$this->error(400, $result[ "error" ]);
		}

		return PostCommentEx::getCommentsForPost($postId);
	}
}
