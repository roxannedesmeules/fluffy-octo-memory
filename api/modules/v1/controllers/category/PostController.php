<?php

namespace app\modules\v1\controllers\category;

use app\helpers\ArrayHelperEx;
use app\modules\v1\components\ControllerEx;
use app\modules\v1\components\parameters\Pagination;
use app\modules\v1\models\CategoryEx;
use app\modules\v1\models\post\PostEx;
use yii\data\ArrayDataProvider;

/**
 * Class PostController
 *
 * @package app\modules\v1\controllers\category
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
	 * @return array
	 *
	 * @SWG\Get(
	 *     path = "/categories/posts/count",
	 *     tags = { "Categories" },
	 *     summary = "Get number of post for categories each categories",
	 *     description = "Count the number of published posts for each active categories",
	 *
	 *     @SWG\Response( response = 200, description = "list of count",  ),
	 * )
	 */
	public function actionCount ()
	{
		return CategoryEx::countPostsByCategories();
	}

	public function actionIndex ( $categoryId )
	{
		if (!CategoryEx::idExists($categoryId)) {
			return $this->notFound("Category not found");
		}

		$data = [
			"allModels"  => PostEx::getAllWithLanguage( $categoryId ),
			"pagination" => $this->pagination,
		];

		return new ArrayDataProvider($data);
	}
}
