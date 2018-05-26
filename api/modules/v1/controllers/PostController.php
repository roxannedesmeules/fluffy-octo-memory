<?php

namespace app\modules\v1\controllers;

use app\helpers\ArrayHelperEx;
use app\modules\v1\components\ControllerEx;
use app\modules\v1\components\parameters\Pagination;
use app\modules\v1\models\post\PostEx;
use yii\data\ArrayDataProvider;

/**
 * Class PostController
 *
 * @package app\modules\v1\controllers
 *
 * @property array $pagination set from Pagination Parameter
 */
class PostController extends ControllerEx
{
	/** @inheritdoc */
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(),
			[
				"Pagination" => Pagination::className(),
			]);
	}

	/**
	 * @return \yii\data\ActiveDataProvider
	 */
	public function actionIndex ()
	{
		$data = [
			"allModels"  => PostEx::getAllWithLanguage(),
			"pagination" => $this->pagination,
		];

		return new ArrayDataProvider($data);
	}
}
