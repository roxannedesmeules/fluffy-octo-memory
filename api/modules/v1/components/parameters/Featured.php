<?php
namespace app\modules\v1\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Featured
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Featured extends \yii\base\Behavior
{
	/** @var int $featured */
	public $featured = null;

	/** @inheritdoc */
	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getFeatured",
									]);
	}

	public function getFeatured ()
	{
		$request = \Yii::$app->request;

		//  get the is featured flag
		$this->featured = $request->get("featured");
	}

}
