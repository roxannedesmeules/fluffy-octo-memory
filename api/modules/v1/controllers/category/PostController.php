<?php

namespace app\modules\v1\controllers\category;

use app\modules\v1\components\ControllerEx;
use app\modules\v1\models\CategoryEx;

/**
 * Class PostController
 *
 * @package app\modules\v1\controllers\category
 */
class PostController extends ControllerEx
{
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
}
