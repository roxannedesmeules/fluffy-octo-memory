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
	 *     path     = "/v1/admin/posts/statuses",
	 *     tags     = { "Posts", "Post Status" },
	 *     summary  = "Get all post status",
	 *     description = "Return list of post statuses",
	 *     
	 *     @SWG\Response( response = 200, description = "post statuses list", @SWG\Schema( ref = "#/definitions/PostStatusList" ), ),
	 * )
	 */
	public function actionIndex ()
	{
		return PostStatusEx::getAllStatuses();
	}
}
