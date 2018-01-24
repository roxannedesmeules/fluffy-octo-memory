<?php

namespace app\modules\v1\admin\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Status
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Status extends \yii\base\behavior
{
	public $status = -1;

	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getStatus",
									]);
	}

	public function getStatus ( $event )
	{
		$request      = \Yii::$app->request;
		$this->status = $request->get("status", -1);
	}
}
