<?php

namespace app\modules\v1\components\headers;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Language
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Language extends \yii\base\behavior
{
	const HEADER_NAME = "Accept-Language";
	const DEFAULT     = "en-ca";

	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getLanguage",
									]);
	}

	public function getLanguage ()
	{
		//  get request headers
		$headers = \Yii::$app->request->getHeaders();

		//  assign language
		\Yii::$app->language = $headers->get(self::HEADER_NAME, self::DEFAULT);
	}
}
