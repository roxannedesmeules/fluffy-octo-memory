<?php

namespace app\modules\v1\admin\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Replied
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Replied extends \yii\base\Behavior
{
	/** @var boolean */
	public $replied = -1;

	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(), [
				Controller::EVENT_BEFORE_ACTION => "getReplied",
			]);
	}

	/**
	 * @param $event
	 */
	public function getReplied ( $event )
	{
		$request       = \Yii::$app->request;
		$this->replied = $request->get("replied", -1);
	}
}
