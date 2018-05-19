<?php

namespace app\modules\v1\controllers;

use app\modules\v1\components\ControllerEx;
use app\modules\v1\models\TagEx;

/**
 * Class TagController
 *
 * @package app\modules\v1\controllers
 */
class TagController extends ControllerEx
{
	/**
	 * @return \app\models\category\CategoryBase[]|array
	 */
	public function actionIndex ()
	{
		return TagEx::getAllWithLanguage();
	}
}
