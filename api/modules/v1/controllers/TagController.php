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

	public function actionView ( $slug )
	{
		if (!TagEx::slugExists($slug)) {
			return $this->notFound("Tag could not be found");
		}

		return TagEx::getOneBySlugWithLanguage($slug);
	}
}
