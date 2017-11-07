<?php
namespace app\modules\v1\admin\controllers;

use app\modules\v1\admin\components\ControllerAdminEx;

/**
 * Class PostController
 *
 * @package app\modules\v1\admin\controllers
 */
class PostController extends ControllerAdminEx
{
	/**
	 * @SWG\Get(
	 *     path    = "/posts",
	 *     tags    = { "Posts" },
	 *     summary = "Get all posts",
	 *     description = "Return list of all posts, with sorting and pagination",
	 *
	 *     @SWG\Response( response = 200, description = "list of posts", @SWG\Schema( ref = "#/definitions/PostList" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex () {}

	/**
	 * @SWG\Post(
	 *     path     = "/posts",
	 *     tags     = { "Posts" },
	 *     summary  = "Create a post",
	 *     description = "create a post with translations",
	 *
	 *
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionCreate () {}

	/**
	 * @SWG\Get(
	 *     path     = "/posts/:id",
	 *     tags     = { "Posts" },
	 *     summary  = "Get a single post",
	 *     description = "Get a post with a specific ID",
	 *
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionView ( $id ) {}

	/**
	 * @SWG\Put(
	 *     path     = "/posts/:id",
	 *     tags     = { "Posts" },
	 *     summary  = "Update a post",
	 *     description = "Update a post and its translations",
	 *
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionUpdate ( $id ) {}

	/**
	 * @SWG\Delete(
	 *     path     = "/posts/:id",
	 *     tags     = { "Posts" },
	 *     summary  = "Delete a post",
	 *     description = "Delete a post and its translations",
	 *
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionDelete ( $id ) {}
}