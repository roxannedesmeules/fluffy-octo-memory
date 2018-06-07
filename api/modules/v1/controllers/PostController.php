<?php

namespace app\modules\v1\controllers;

use app\helpers\ArrayHelperEx;
use app\modules\v1\components\ControllerEx;
use app\modules\v1\components\parameters\Category;
use app\modules\v1\components\parameters\Pagination;
use app\modules\v1\models\post\PostEx;
use yii\data\ArrayDataProvider;

/**
 * Class PostController
 *
 * @package app\modules\v1\controllers
 *
 * @property array $pagination set from Pagination Parameter
 * @property int   $category   set from Category Parameter
 */
class PostController extends ControllerEx
{
	/** @inheritdoc */
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(),
			[
				"Pagination" => Pagination::className(),
				"Category"   => Category::className(),
			]);
	}

	/**
	 * @return \yii\data\ActiveDataProvider
	 */
	public function actionIndex ()
	{
		$filters = [
			"category" => $this->category,
		];

		$data = [
			"allModels"  => PostEx::getAllWithLanguage($filters),
			"pagination" => $this->pagination,
		];

		return new ArrayDataProvider($data);
	}

	public function actionView ( $slug ) {
		return PostEx::getOneBySlugWithLanguage($slug);
	}
}
