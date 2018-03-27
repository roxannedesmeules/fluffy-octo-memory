<?php

namespace app\modules\v1\admin\controllers\post;

use app\helpers\ArrayHelperEx;
use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\components\parameters\Language;
use app\modules\v1\admin\components\parameters\Pagination;
use app\modules\v1\admin\components\parameters\Status;
use app\modules\v1\admin\models\post\PostEx;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class PostController
 *
 * @package app\modules\v1\admin\controllers\post
 *
 * @property integer $status     set from Status Parameter
 * @property integer $language   set from Language Parameter
 * @property array   $pagination set from Pagination Parameter
 */
class PostController extends ControllerAdminEx
{
	/** @inheritdoc */
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(),
									[
										"Status"     => Status::className(),
										"Language"   => Language::className(),
										"Pagination" => Pagination::className(),
									]);
	}

	/**
	 * @SWG\Get(
	 *     path    = "/posts",
	 *     tags    = { "Posts" },
	 *     summary = "Get all posts",
	 *     description = "Return list of all posts, with sorting and pagination",
	 *
	 *     @SWG\Parameter(
	 *            name = "status", in = "query", type = "integer", default = "-1", enum = { -1, 1 , 2, 3, 4 },
	 *            description = "filter by post statuses, either remove the parameter or set to -1 to get all",
	 *       ),
	 *
	 *     @SWG\Response( response = 200, description = "list of posts", @SWG\Schema( ref = "#/definitions/PostList" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex ()
	{
		$filters = [
			"status"   => $this->status,
			"language" => $this->language,
		];

		$data = [
			"allModels"  => PostEx::getAllWithTranslations($filters),
			//  TODO    add sorting
			"pagination" => $this->pagination,
		];

		return new ArrayDataProvider($data);
	}

	/**
	 * @SWG\Post(
	 *     path     = "/posts",
	 *     tags     = { "Posts" },
	 *     summary  = "Create a post",
	 *     description = "create a post with translations",
	 *
	 *     @SWG\Parameter( name = "post", in = "body", required = true, @SWG\Schema( ref = "#/definitions/PostForm" ),),
	 *
	 *     @SWG\Response( response = 201, description = "post created succesfully", @SWG\Schema( @SWG\Property( property = "post_id", type = "integer" ), ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "post to be created isn't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while creating post", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionCreate ()
	{
		//  get the request data and create a Post model with it
		$form = new PostEx($this->request->getBodyParams());

		//  validate the form content and return 422 error if not valid
		if ( !$form->validate() ) {
			return $this->unprocessableResult($form->getErrors());
		}

		//  create the post with translations and keep the result
		$result = PostEx::createWithTranslations($form, $form->translations);

		//  in case of error, trigger the right one
		if ( $result[ "status" ] === PostEx::ERROR ) {
			switch ( $result[ "error" ] ) {
				case PostEx::ERR_ON_SAVE :
					throw new ServerErrorHttpException(PostEx::ERR_ON_SAVE);

				case PostEx::ERR_CATEGORY_NOT_FOUND :
					//  no break;
				case PostEx::ERR_STATUS_NOT_FOUND :
					//  no break;
				default :
					return $this->unprocessableResult($result[ "error" ]);
			}
		}

		return $this->createdResult([ "post_id" => $result[ "post_id" ] ]);
	}

	/**
	 * @SWG\Get(
	 *     path     = "/posts/:id",
	 *     tags     = { "Posts" },
	 *     summary  = "Get a single post",
	 *     description = "Get a post with a specific ID",
	 *
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *
	 *     @SWG\Response( response = 200, description = "single post with translation", @SWG\Schema( ref = "#/definitions/Post" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "post can't be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionView ( $id )
	{
		if (!PostEx::idExists($id)) {
			throw new NotFoundHttpException(PostEx::ERR_NOT_FOUND);
		}

		return PostEx::getOneWithTranslations($id);
	}

	/**
	 * @SWG\Put(
	 *     path     = "/posts/:id",
	 *     tags     = { "Posts" },
	 *     summary  = "Update a post",
	 *     description = "Update a post and its translations",
	 *
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *     @SWG\Parameter( name = "post", in = "body", required = true, @SWG\Schema( ref = "#/definitions/PostForm" ), ),
	 *
	 *     @SWG\Response( response = 200, description = "post correctly updated", @SWG\Schema( ref = "#/definitions/Post" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "post not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "category to be created isn't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while creating category", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionUpdate ( $id )
	{
		//  get request data and create post model with it
		$form = new PostEx($this->request->getBodyParams());

		if ( !$form->validate() ) {
			return $this->unprocessableResult($form->getErrors());
		}

		//  update the post with translation and return result
		$result = PostEx::updateWithTranslations($id, $form, $form->translations);

		if ($result[ "status" ] === PostEx::ERROR) {
			switch ($result[ "error" ]) {
				case PostEx::ERR_NOT_FOUND :
					throw new NotFoundHttpException(PostEx::ERR_NOT_FOUND);

				case PostEx::ERR_ON_SAVE :
					throw new ServerErrorHttpException(PostEx::ERR_ON_SAVE);

				default :
					if ( is_array($result[ "error" ]) ) {
						$this->unprocessableResult($result[ "error" ]);
					}

					//  if here, then error wasn't handled properly but should still be thrown
					throw new ServerErrorHttpException(json_encode($result[ "error" ]));
			}
		}

		return [ "post" => $result[ "post" ] ];
	}

	/**
	 * @SWG\Delete(
	 *     path     = "/posts/:id",
	 *     tags     = { "Posts" },
	 *     summary  = "Delete a post",
	 *     description = "Delete a post and its translations",
	 *
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *
	 *     @SWG\Response( response = 204, description = "post correctly deleted", ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "post not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 500, description = "error while deleting post", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionDelete ( $id )
	{
		$result = PostEx::deleteWithTranslations($id);

		//  if the status is an error, then define what to do depending on the content
		if ( $result[ "status" ] === PostEx::ERROR ) {
			switch ( $result[ "error" ] ) {
				case PostEx::ERR_NOT_FOUND :
					throw new NotFoundHttpException(PostEx::ERR_NOT_FOUND);

				case PostEx::ERR_ON_DELETE :
					throw new ServerErrorHttpException(PostEx::ERR_ON_DELETE);

				default :
					if ( is_array($result[ "error" ]) ) {
						$this->response->setStatusCode(500);

						return $result[ "error" ];
					}

					//  if here, then error wasn't handled properly but should still be thrown
					throw new ServerErrorHttpException(json_encode($result[ "error" ]));
			}
		}

		$this->response->setStatusCode(204);
	}
}