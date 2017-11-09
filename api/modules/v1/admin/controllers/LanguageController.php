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

	/**
	 * @SWG\Get(
	 *     path     = "/languages",
	 *     tags     = { "Languages" },
	 *     summary  = "Get all languages",
	 *     description = "Get a list of all languages supported by the application",
	 *
	 *     @SWG\Response( response = 200, description = "List of languages", @SWG\Schema( ref = "#/definitions/Languages" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex ()
	{
		return LangEx::getAllLanguages();
	}
}
