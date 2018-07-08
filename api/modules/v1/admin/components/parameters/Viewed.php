<?php

namespace app\modules\v1\admin\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Viewed
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Viewed extends \yii\base\Behavior
{
	/** @var boolean */
	public $viewed = -1;

	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(), [
			Controller::EVENT_BEFORE_ACTION => "getViewed",
		]);
	}

	/**
	 * @param $event
	 */
	public function getViewed ( $event )
	{
		$request      = \Yii::$app->request;
		$this->viewed = $request->get("viewed", -1);
	}
}
