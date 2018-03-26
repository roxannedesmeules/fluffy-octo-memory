<?php

namespace app\modules\v1\admin\controllers;

use app\helpers\ArrayHelperEx;
use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\components\parameters\Language;
use app\modules\v1\admin\components\parameters\Pagination;
use app\modules\v1\admin\models\tag\TagEx;
use yii\data\ArrayDataProvider;

/**
 * Class TagController
 *
 * @package app\modules\v1\admin\controllers
 *
 * @property integer $language   set from Language Parameter
 * @property array   $pagination set from Pagination Parameter
 */
class TagController extends ControllerAdminEx
{
	/** @inheritdoc */
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(),
			[
				"Language"   => Language::className(),
				"Pagination" => Pagination::className(),
			]);
	}

	/**
	 * @SWG\Get(
	 *     path = "/tags",
	 *     tags = { "Tags" },
	 *     summary = "Get all tags",
	 *     description = "Return list of all tags, with filtering, sorting and pagination",
	 *
	 *     @SWG\Parameter( name = "lang", in = "query", type = "string" ),
	 *     @SWG\Parameter( name = "page", in = "query", type = "integer" ),
	 *     @SWG\Parameter( name = "per-page", in = "query", type = "integer" ),
	 *
	 *     @SWG\Response( response = 200, description = "List of tags", @SWG\Schema( ref = "#/definitions/TagList" ), ),
	 *     @SWG\Response( response = 401, description = "User can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 403, description = "Invalid or missing API Client", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex ()
	{
		$filters = [
			"language" => $this->language,
		];

		$data = [
			"allModels"  => TagEx::getAllWithTranslations($filters),
			"pagination" => $this->pagination,
			//  TODO    add sorting
		];

		return new ArrayDataProvider($data);
	}

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
	public function actionCreate ()
	{

	}

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
