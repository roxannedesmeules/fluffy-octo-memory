<?php

namespace app\modules\v1\admin\controllers;

use app\helpers\ArrayHelperEx;
use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\components\parameters\Active;
use app\modules\v1\admin\components\parameters\Language;
use app\modules\v1\admin\components\parameters\Pagination;
use app\modules\v1\admin\models\category\CategoryEx;
use app\modules\v1\admin\models\category\CategoryLangEx;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CategoryController
 *
 * @package app\modules\v1\admin\controllers
 *
 * @property boolean $active     set from Active Parameter
 * @property string  $language   set from Language Parameter
 * @property array   $pagination set from Pagination Parameter
 */
class CategoryController extends ControllerAdminEx
{
	/** @inheritdoc */
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(),
									[
										"Active"     => Active::className(),
										"Language"   => Language::className(),
										"Pagination" => Pagination::className(),
									]);
	}

	/**
	 * @SWG\Get(
	 *     path = "/categories",
	 *     tags = { "Categories" },
	 *     summary = "Get all Categories",
	 *     description = "Get list of all categories, returned with sorting and pagination",
	 *
	 *     @SWG\Parameter(
	 *          name = "active", in = "query", type = "integer", default="-1", enum={ -1, 0, 1 },
	 *          description = "Fetch active or inactive categories only, remove to fetch all"
	 *     ),
	 *
	 *     @SWG\Response( response = 200, description = "list of categories", @SWG\Schema( ref = "#/definitions/Categories" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex ()
	{
		$filters = [
			"active"   => $this->active,
			"language" => $this->language,
		];

		$data = [
			"allModels"  => CategoryEx::getAllWithTranslations($filters),
			//  TODO    add sorting
			"pagination" => $this->pagination,
		];

		return new ArrayDataProvider($data);
	}

	/**
	 * @SWG\Get(
	 *     path = "/categories/:id",
	 *     tags = { "Categories" },
	 *     summary = "Get a single category",
	 *     description = "Get a category with a specific ID",
	 *
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *
	 *     @SWG\Response( response = 200, description = "single category", @SWG\Schema( ref = "#/definitions/Category" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "category not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionView ( $id )
	{
		if (!CategoryEx::idExists($id)) {
			throw new NotFoundHttpException(CategoryEx::ERR_NOT_FOUND);
		}

		return CategoryEx::getOneWithTranslations($id);
	}

	/**
	 * @SWG\Post(
	 *     path = "/categories",
	 *     tags = { "Categories" },
	 *     summary = "Create a category",
	 *     description = "Create a new category with translations",
	 *
	 *     @SWG\Parameter( name = "category", in = "body", required = true, @SWG\Schema( ref = "#/definitions/CategoryForm" ), ),
	 *
	 *     @SWG\Response( response = 201, description = "category id", @SWG\Schema( ref = "#/definitions/Category" ),),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "category to be created isn't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while creating category", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionCreate ()
	{
		//  get request data and create a Category with it
		$form = new CategoryEx($this->request->getBodyParams());

		//  validate form
		if ( !$form->validate() ) {
			return $this->unprocessableResult($form->getErrors());
		}

		//  create the category with translation and return result
		$result = CategoryEx::createWithTranslations($form, $form->translations);

		//  if the status is an error, then define what to do depending on the content
		if ( $result[ "status" ] === CategoryEx::ERROR ) {
			switch ( $result[ "error" ] ) {
				case CategoryEx::ERR_ON_SAVE :
					throw new ServerErrorHttpException(CategoryEx::ERR_ON_SAVE);

				case CategoryLangEx::ERR_LANG_NOT_FOUND :
					throw new UnprocessableEntityHttpException(CategoryLangEx::ERR_LANG_NOT_FOUND);

				default :
					if ( is_array($result[ "error" ]) ) {
						$this->unprocessableResult($result[ "error" ]);
					}
			}
		}


		//  return newly created category
		return $this->createdResult(CategoryEx::getOneWithTranslations($result[ "category_id" ]));
	}

	/**
	 * @SWG\Put(
	 *     path = "/categories/:id",
	 *     tags = { "Categories" },
	 *     summary = "Update a category",
	 *     description = "Update an existing category and its translations",
	 *
	 *     @SWG\Parameter( name = "id", in = "path", type = "integer", required = true ),
	 *     @SWG\Parameter( name = "category", in = "body", required = true, @SWG\Schema( ref = "#/definitions/CategoryForm" ), ),
	 *
	 *     @SWG\Response( response = 200, description = "updated category", @SWG\Schema( ref = "#/definitions/Category" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "category not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "category to be created isn't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while updating category", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionUpdate ( $id )
	{
		//  get request data and create a Category with it so it can be validated
		$form = new CategoryEx($this->request->getBodyParams());

		//  validate form
		if ( !$form->validate() ) {
			return $this->unprocessableResult($form->getErrors());
		}

		//  update the category with translation and return result
		$result = CategoryEx::updateWithTranslations($id, $form, $form->translations);

		//  if the status is an error, then define what to do depending on the content
		if ( $result[ "status" ] === CategoryEx::ERROR ) {
			switch ( $result[ "error" ] ) {
				case CategoryEx::ERR_NOT_FOUND :
					throw new NotFoundHttpException(CategoryEx::ERR_NOT_FOUND);

				case CategoryEx::ERR_ON_SAVE :
					throw new ServerErrorHttpException(CategoryEx::ERR_ON_SAVE);

				case CategoryLangEx::ERR_LANG_NOT_FOUND :
					throw new UnprocessableEntityHttpException(CategoryLangEx::ERR_LANG_NOT_FOUND);

				case CategoryLangEx::ERR_ON_SAVE :
					throw new ServerErrorHttpException(CategoryLangEx::ERR_ON_SAVE);

				default :
					if ( is_array($result[ "error" ]) ) {
						$this->unprocessableResult($result[ "error" ]);
					}

					//  if here, then error wasn't handled properly but should still be thrown
					throw new ServerErrorHttpException(json_encode($result[ "error" ]));
			}
		}

		//  return the updated
		return [ "category" => $result[ "category" ] ];
	}

	/**
	 * @SWG\Delete(
	 *     path = "/category/:id",
	 *     tags = { "Categories" },
	 *     summary = "Delete a category",
	 *     description = "Delete an existing category and its translations",
	 *
	 *     @SWG\Response( response = 204, description = "category correctly deleted" ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "category not found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 500, description = "error while updating category", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionDelete ( $id )
	{
		$result = CategoryEx::deleteWithTranslations($id);

		//  if the status is an error, then define what to do depending on the content
		if ( $result[ "status" ] === CategoryEx::ERROR ) {
			switch ( $result[ "error" ] ) {
				case CategoryEx::ERR_NOT_FOUND :
					throw new NotFoundHttpException(CategoryEx::ERR_NOT_FOUND);

				case CategoryEx::ERR_ON_DELETE :
					throw new ServerErrorHttpException(CategoryEx::ERR_ON_DELETE);

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
