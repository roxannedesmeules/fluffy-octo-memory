<?php

namespace app\modules\v1\admin\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Active
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Active extends \yii\base\Behavior
{
	/** @var boolean */
	public $active = -1;

	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getActive",
									]);
	}

	/**
	 * @param $event
	 */
	public function getActive ( $event )
	{
		$request      = \Yii::$app->request;
		$this->active = $request->get("active", -1);
	}
}
