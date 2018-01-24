<?php

namespace app\modules\v1\admin\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Language
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Language extends \yii\base\behavior
{
	/** @var string */
	public $language;

	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getLanguage",
									]);
	}

	public function getLanguage ()
	{
		$request        = \Yii::$app->request;
		$this->language = $request->get("lang", null);
	}
}
