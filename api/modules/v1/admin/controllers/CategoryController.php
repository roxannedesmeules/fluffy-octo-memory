<?php
namespace app\modules\v1\admin\controllers;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\category\CategoryEx;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;

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
		
		//  create the category with translation and return result
		return CategoryEx::createWithTranslations($form, $form->translations);
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
