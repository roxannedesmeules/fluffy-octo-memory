<?php
namespace app\modules\v1\components\parameters;

use app\helpers\ArrayHelperEx;
use app\modules\v1\models\TagEx;
use yii\rest\Controller;

/**
 * Class Tag
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Tag extends \yii\base\Behavior
{
	/** @var int $tag */
	public $tag = null;

	/** @inheritdoc */
	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getTag",
									]);
	}

	public function getTag ()
	{
		$request = \Yii::$app->request;

		//  get the category slug
		$slug = $request->get("tag");

		//  get the tag ID if there is a slug passed
		if (!empty($slug)) {
			if (TagEx::slugExists($slug)) {
				$this->tag = TagEx::find()->withSlug($slug)->one()->id;
			} else {
				$this->tag = -1;
			}
		}
	}

}
