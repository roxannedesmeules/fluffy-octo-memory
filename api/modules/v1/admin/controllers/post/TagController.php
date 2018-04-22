<?php

namespace app\modules\v1\admin\controllers\post;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\tag\AssoTagPostEx;

/**
 * Class TagController
 *
 * @package app\modules\v1\admin\controllers\post
 */
class TagController extends ControllerAdminEx
{
	/** @var array  */
	public $corsMethods = [ "OPTIONS", "POST", "DELETE" ];

	/** @inheritdoc */
	protected function verbs ()
	{
		return [
			"create" => [ "OPTIONS", "POST" ],
			"delete" => [ "OPTIONS", "DELETE" ],
		];
	}

	/**
	 * @SWG\Post(
	 *     path = "/posts-tags",
	 *     tags = { "Posts", "Tags" },
	 *     summary = "Create a relation between a post and a tag",
	 *     description = "Create a relationship entry between a post and a tag",
	 *
	 *     @SWG\Parameter( name = "relation", in = "body", required = true, @SWG\Schema( ref = "#/definitions/PostTagRelation" ), ),
	 *
	 *     @SWG\Response( response = 204, description = "Relation created" ),
	 *     @SWG\Response( response = 404, description = "Post or Tag couldn't be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "Relation to be created is invalid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 * )
	 */
	public function actionCreate ()
	{
		$form             = new AssoTagPostEx();
		$form->attributes = $this->request->getBodyParams();

		if (!$form->validate()) {
			$this->unprocessableResult($form->getErrors());
		}

		$result = AssoTagPostEx::createRelation($form->post_id, $form->tag_id);

		if ($result[ "status" ] === AssoTagPostEx::ERROR) {
			switch ($result[ "error" ]) {
				case AssoTagPostEx::ERR_POST_NOT_FOUND :
					//  no break
				case AssoTagPostEx::ERR_TAG_NOT_FOUND :
					return $this->error(404, $result[ "error" ]);

				case AssoTagPostEx::ERR_ALREADY_EXISTS :
					return $this->error(400, $result[ "error" ]);

				default :
					return $this->error(500, $result[ "error" ]);
			}
		}

		return $this->emptySuccess();
	}

	/**
	 * @SWG\Delete(
	 *     path = "/posts-tags",
	 *     tags = { "Posts", "Tags" },
	 *     summary = "Delete a relation between a post and a tag",
	 *     description = "Delete a relationship entry between a post and a tag",
	 *
	 *     @SWG\Parameter( name = "relation", in = "body", required = true, @SWG\Schema( ref = "#/definitions/PostTagRelation" ), ),
	 *
	 *     @SWG\Response( response = 204, description = "Relation deleted" ),
	 *     @SWG\Response( response = 404, description = "Relation couldn't be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "Relation to be deleted is invalid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 * )
	 */
	public function actionDelete ()
	{
		$form             = new AssoTagPostEx();
		$form->attributes = $this->request->getBodyParams();

		if (!$form->validate()) {
			$this->unprocessableResult($form->getErrors());
		}

		$result = AssoTagPostEx::deleteRelation($form->post_id, $form->tag_id);

		if ($result[ "status" ] === AssoTagPostEx::ERROR) {
			switch ($result[ "error" ]) {
				case AssoTagPostEx::ERR_NOT_FOUND :
					return $this->error(400, $result[ "error" ]);

				default :
					return $this->error(500, $result[ "error" ]);
			}
		}

		return $this->emptySuccess();
	}
}
