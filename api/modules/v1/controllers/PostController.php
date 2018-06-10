<?php

namespace app\modules\v1\controllers;

use app\helpers\ArrayHelperEx;
use app\modules\v1\components\ControllerEx;
use app\modules\v1\components\parameters\Category;
use app\modules\v1\components\parameters\Featured;
use app\modules\v1\components\parameters\Pagination;
use app\modules\v1\components\parameters\Tag;
use app\modules\v1\models\post\PostEx;
use yii\data\ArrayDataProvider;

/**
 * Class PostController
 *
 * @package app\modules\v1\controllers
 *
 * @property array $pagination set from Pagination Parameter
 * @property int   $category   set from Category Parameter
 * @property int   $tag        set from Tag Parameter
 * @property int   $featured   set from Featured Parameter
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
				"Tag"        => Tag::className(),
				"Featured"   => Featured::className()
			]);
	}

	/**
	 * @return \yii\data\ArrayDataProvider
	 */
	public function actionIndex ()
	{
		$filters = [
			"category" => $this->category,
			"tag"      => $this->tag,
			"featured" => $this->featured,
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
