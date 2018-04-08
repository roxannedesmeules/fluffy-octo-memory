<?php

namespace app\modules\v1\admin\controllers\tag;

use app\helpers\ArrayHelperEx;
use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\components\parameters\Language;
use app\modules\v1\admin\components\parameters\Pagination;
use app\modules\v1\admin\models\tag\TagEx;
use yii\data\ArrayDataProvider;

/**
 * Class TagController
 *
 * @package app\modules\v1\admin\controllers\tag
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
		//  get the request data and create a Tag model with it
		$form = new TagEx($this->request->getBodyParams());

		//  validate the form content and return 422 error if not valid
		if ( !$form->validate() ) {
			return $this->unprocessableResult($form->getErrors());
		}

		//  create the tag with translations and keep the result
		$result = TagEx::createWithTranslations($form->translations);

		//  in case of error, trigger the right one
		if ( $result[ "status" ] === TagEx::ERROR ) {
			switch ($result[ "error" ]) {
				case TagEx::ERR_ON_SAVE :
					return $this->error(500, TagEx::ERR_ON_SAVE);

				default :
					return $this->unprocessableResult($result[ "error" ]);
			}
		}

		return $this->createdResult(TagEx::getOneWithTranslations($result[ "tag_id" ]));
	}

	/**
	 * @param $id
	 *
	 * @return \app\models\tag\TagBase|array|null
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
	public function actionView ( $id )
	{
		if ( !TagEx::idExists($id) ) {
			return $this->error(404, TagEx::ERR_NOT_FOUND);
		}

		return TagEx::getOneWithTranslations($id);
	}

	/**
	 * @param $id
	 *
	 * @return array
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\db\Exception
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
	public function actionUpdate ( $id )
	{
		//  get request data and create Tag model with it
		$form = new TagEx($this->request->getBodyParams());

		//  validate form
		if ( !$form->validate() ) {
			return $this->unprocessableResult($form->getErrors());
		}

		//  update the tag with translation and return result
		$result = TagEx::updateWithTranslations($id, $form->translations);

		//  in case of error, trigger the right one
		if ( $result[ "status" ] === TagEx::ERROR ) {
			switch ($result[ "error" ]) {
				case TagEx::ERR_ON_SAVE :
					return $this->error(500, TagEx::ERR_ON_SAVE);

				case TagEx::ERR_NOT_FOUND :
					return $this->error(404, TagEx::ERR_NOT_FOUND);

				default :
					if ( is_array($result[ "error" ]) ) {
						return $this->unprocessableResult($result[ "error" ]);
					}

					return $this->error(500, $result[ "error" ]);
			}
		}

		//  return updated tag
		return [ "post" => $result[ "post" ] ];
	}

	/**
	 * @param $id
	 *
	 * @return array
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\Exception
	 * @throws \yii\db\StaleObjectException
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
	public function actionDelete ( $id )
	{
		$result = TagEx::deleteWithTranslations($id);

		if ( $result[ "status" ] === TagEx::ERROR ) {
			switch ($result[ "error" ]) {
				case TagEx::ERR_NOT_FOUND :
					return $this->error(404, TagEx::ERR_NOT_FOUND);

				case TagEx::ERR_ON_DELETE :
					return $this->error(500, TagEx::ERR_ON_DELETE);

				default :
					return $this->error(500, $result["error"]);
			}
		}

		return $this->emptySuccess();
	}
}
