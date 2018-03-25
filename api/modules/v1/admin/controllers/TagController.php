<?php

namespace app\modules\v1\admin\controllers;

use app\modules\v1\admin\components\ControllerAdminEx;

/**
 * Class TagController
 *
 * @package app\modules\v1\admin\controllers
 */
class TagController extends ControllerAdminEx
{
	/**
	 * @SWG\Get(
	 *     path = "/tags",
	 *     tags = { "Tags" },
	 *     summary = "Get all tags",
	 *     description = "Return list of all tags, with sorting and pagination",
	 *
	 *     @SWG\Response( response = 200, description = "List of tags", @SWG\Schema( ref = "#/definitions/TagList" ), ),
	 *     @SWG\Response( response = 401, description = "User can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 403, description = "Invalid or missing API Client", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex () {}

	/**
	 * @SWG\Post(
	 *     path = "/tags",
	 *     tags = { "Tags" },
	 *     summary = "Create a tag",
	 *     description = "Create a tag with translations",
	 *
	 *     @SWG\Parameter( name = "tag", in = "body", required = true, @SWG\Schema( ref = "#/definitions/TagForm" ), ),
	 *
	 *     @SWG\Response( response = 201, description = "Tag created successfully", @SWG\Schema( @SWG\Property( property = "tag_id", type = "integer" ), ), ),
	 *     @SWG\Response( response = 401, description = "User can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 403, description = "Invalid of missing API Client", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "Tag to create is invalid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 * )
	 */
	public function actionCreate () {}

	/**
	 * @param $id
	 *
	 * @SWG\Get(
	 *     path = "/tags/:id",
	 *     tags = { "Tags" },
	 *     summary = "Get a single tag",
	 *     description = "Get a single tag with a specific ID",
	 *
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *
	 *     @SWG\Response( response = 200, description = "Single tag", @SWG\Schema( ref = "#/definitions/Tag" ), ),
	 *     @SWG\Response( response = 401, description = "User can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 403, description = "Invalid of missing API Client", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "Tag could not be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionView ( $id ) {}

	/**
	 * @param $id
	 * 
	 * @SWG\Put(
	 *     path = "/tags/:id",
	 *     tags = { "Tags" },
	 *     summary = "Update a single tag",
	 *     description = "Update a tag with a specific ID and its translations",
	 *
	 *     @SWG\Parameter( name = "id",  in = "path", type = "integer", required = true ),
	 *     @SWG\Parameter( name = "tag", in = "body", required = true, @SWG\Schema( ref = "#/definitions/TagForm" ), ),
	 *
	 *     @SWG\Response( response = 200, description = "tag updated successfully", @SWG\Schema( ref = "#/definitions/Tag" ), ),
	 *     @SWG\Response( response = 401, description = "User can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 403, description = "Invalid of missing API Client", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "Tag could not be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "Tag to create is invalid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 * )
	 */
	public function actionUpdate ( $id ) {}

	/**
	 * @param $id
	 *
	 * @SWG\Delete(
	 *     path = "/tags/:id",
	 *     tags = { "Tags" },
	 *     summary = "Delete a single tag",
	 *     description = "Delete a tag with a specific ID and all of its translations",
	 *
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *     
	 *     @SWG\Response( response = 204, description = "Tag deleted successfully" ),
	 *     @SWG\Response( response = 401, description = "User can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 403, description = "Invalid of missing API Client", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "Tag could not be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionDelete ( $id ) {}
}
