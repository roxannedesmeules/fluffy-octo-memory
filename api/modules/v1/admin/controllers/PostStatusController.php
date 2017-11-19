<?php
namespace app\modules\v1\admin\controllers;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\posts\PostStatusEx;

/**
 * Class PostStatusController
 *
 * @package app\modules\v1\admin\controllers\posts
 */
class PostStatusController extends ControllerAdminEx
{
	public $corsMethods = [ "OPTIONS", "GET" ];

	/**
	 * @SWG\Get(
	 *     path     = "/posts/statuses",
	 *     tags     = { "Posts", "Post Statuses" },
	 *     summary  = "Get all post status",
	 *     description = "Return list of post statuses",
	 *     
	 *     @SWG\Response( response = 200, description = "post statuses list", @SWG\Schema( ref = "#/definitions/PostStatusList" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionIndex ()
	{
		return PostStatusEx::getAllStatuses();
	}
}
