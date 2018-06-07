<?php
namespace app\modules\v1\components\parameters;

use app\helpers\ArrayHelperEx;
use app\modules\v1\models\CategoryEx;
use yii\rest\Controller;

/**
 * Class Category
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Category extends \yii\base\Behavior
{
	/** @var category     contains */
	public $category = null;

	/** @inheritdoc */
	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getCategory",
									]);
	}

	public function getCategory ( $event )
	{
		$request = \Yii::$app->request;

		//  get the category slug
		$slug = $request->get("category");

		//  get the category ID if there is a slug passed
		if (!empty($slug)) {
			if (CategoryEx::slugExists($slug)) {
				$this->category = CategoryEx::find()->withSlug($slug)->one()->id;
			} else {
				$this->category = -1;
			}
		}
	}

}
