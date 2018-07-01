<?php

namespace app\modules\v1\controllers\post;

use app\modules\v1\components\ControllerEx;
use app\modules\v1\models\post\PostCommentEx;
use app\modules\v1\models\post\PostEx;

/**
 * Class CommentController
 *
 * @package app\modules\v1\controllers\post
 */
class CommentController extends ControllerEx
{
	public $corsMethods = [ "OPTIONS", "GET", "POST" ];

	/** @inheritdoc */
	protected function verbs ()
	{
		return [
			"index"  => [ "OPTIONS", "GET" ],
			"create" => [ "OPTIONS", "POST" ],
		];
	}

	public function actionIndex ( $postId ) {}

	public function actionCreate ( $postId )
	{
		$form = new PostCommentEx();

		$form->setAttributes($this->request->getBodyParams());
		$form->post_id = $postId;

		//  validate the form content and return 422 error if not valid
		if ( !$form->validate() ) {
			return $this->unprocessableResult($form->getErrors());
		}
		
		$result = PostCommentEx::createForPost($postId, $form);

		//  in case of error, trigger the right one
		if ( $result[ "status" ] === PostCommentEx::ERROR ) {
			switch ( $result[ "error" ] ) {
				case PostCommentEx::ERR_POST_NOT_FOUND :
					return $this->error(404, PostCommentEx::ERR_POST_NOT_FOUND);

				case PostCommentEx::ERR_ON_SAVE :
					return $this->error(400, PostCommentEx::ERR_ON_SAVE);

				default :
					return $this->unprocessableResult($result[ "error" ]);
			}
		}

		$this->response->setStatusCode(201);

		return PostEx::getOneByIdWithLanguage($postId);
	}
}
