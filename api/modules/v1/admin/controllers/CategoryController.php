<?php
namespace app\modules\v1\admin\controllers;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\category\CategoryEx;
use app\modules\v1\admin\models\category\CategoryLangEx;
use yii\data\ArrayDataProvider;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CategoryController
 *
 * @package app\modules\v1\admin\controllers
 */
class CategoryController extends ControllerAdminEx
{
	public function actionIndex ()
	{
		return new ArrayDataProvider([
			"allModels" => CategoryEx::getAllWithTranslations(),
			//  TODO    add sorting
			//  TODO    add pagination
		]);
	}
	
	public function actionView ( $id )
	{
		return CategoryEx::getOneWithTranslations($id);
	}
	
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
		if ($result[ "status" ] === CategoryEx::ERROR) {
			switch ($result[ "error" ]) {
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
	
	public function actionUpdate ( $id )
	{
		//  get request data and create a Category with it so it can be validated
		$form = new CategoryEx($this->request->getBodyParams());
		
		//  update the category with translation and return result
		return CategoryEx::updateWithTranslations($id, $form, $form->translations);
	}
	
	public function actionDelete ( $id )
	{
		return CategoryEx::deleteWithTranslations($id);
	}
}
