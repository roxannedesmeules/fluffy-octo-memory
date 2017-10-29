<?php
namespace app\modules\v1\admin\controllers;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\LangEx;

/**
 * Class LanguageController
 *
 * @package app\modules\v1\admin\controllers
 */
class LanguageController extends ControllerAdminEx
{
	public $corsMethods = [ "OPTIONS", "GET" ];

	public function actionIndex ()
	{
		return LangEx::getAllLanguages();
	}
}
