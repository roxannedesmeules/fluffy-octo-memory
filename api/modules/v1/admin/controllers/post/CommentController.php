<?php

namespace app\modules\v1\admin\controllers\post;

use app\modules\v1\admin\components\ControllerAdminEx;
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
	 *     path = "/posts/:postid/comments",
	 *     tags = { "Post comments" },
	 *     summary = "Get all comments linked to a post",
	 *     description = "Get the comment tree linked to a specific post grouped by language.",
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
	 */
	public function actionCreate ( $postId ) {}

	/**
	 * @param int $postId   - post ID to which the comment is linked
	 * @param int $id       - comment ID to update
	 *
	 * @return array
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
