<?php
namespace app\modules\v1\admin\controllers\post;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\post\PostStatusEx;

/**
 * Class StatusController
 *
 * @package app\modules\v1\admin\controllers\post
 */
class StatusController extends ControllerAdminEx
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
