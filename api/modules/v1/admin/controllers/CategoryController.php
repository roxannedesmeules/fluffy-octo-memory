<?php

namespace app\modules\v1\admin\controllers;

use app\modules\v1\admin\components\ControllerAdminEx;
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
 */
class CategoryController extends ControllerAdminEx
{
	/**
	 * @SWG\Get(
	 *     path = "/v1/admin/categories",
	 *     tags = { "Categories" },
	 *     summary = "Get all Categories",
	 *     description = "Get list of all categories, returned with sorting and pagination",
	 *
	 *     @SWG\Response( response = 200, description = "list of categories", @SWG\Schema( ref = "#/definitions/Categories" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex ()
	{
		return new ArrayDataProvider([
			                             "allModels" => CategoryEx::getAllWithTranslations(),
			                             //  TODO    add sorting
			                             //  TODO    add pagination
		                             ]);
	}

	/**
	 * @SWG\Get(
	 *     path = "/v1/admin/categories/:id",
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
		return CategoryEx::getOneWithTranslations($id);
	}

	/**
	 * @SWG\Post(
	 *     path = "/v1/admin/categories",
	 *     tags = { "Categories" },
	 *     summary = "Create a category",
	 *     description = "Create a new category with translations",
	 *     
	 *     @SWG\Parameter( name = "category", in = "body", required = true, @SWG\Schema( ref = "#/definitions/CategoryForm" ), ),
	 *
	 *     @SWG\Response( response = 201, description = "category id", @SWG\Schema( @SWG\Property( property = "category_id", type = "integer" ), ),),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "category to be created isn't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while creating category", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionCreate ()
	{
		//  get request data and create a Category with it
		$form = new CategoryEx($this->request->getBodyParams());

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


		//  return newly created category ID
		$this->response->setStatusCode(201);

		return [ "category_id" => $result[ "category_id" ] ];
	}

	/**
	 * @SWG\Put(
	 *     path = "/v1/admin/categories/:id",
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
	 *     path = "/v1/admin/category/:id",
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
	}
}
