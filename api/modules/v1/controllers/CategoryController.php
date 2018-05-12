<?php

namespace app\modules\v1\controllers;

use app\modules\v1\components\ControllerEx;
use app\modules\v1\models\CategoryEx;

/**
 * Class CategoryController
 *
 * @package app\modules\v1\controllers
 */
class CategoryController extends ControllerEx
{
	/**
	 * @return \app\models\category\CategoryBase[]|array
	 */
	public function actionIndex ()
	{
		return CategoryEx::getAllWithLanguage();
	}

	/**
	 * @param $id
	 *
	 * @return \app\models\category\CategoryBase[]|array
	 */
	public function actionView ( $id )
	{
		return CategoryEx::getOneWithLanguage($id);
	}
}
