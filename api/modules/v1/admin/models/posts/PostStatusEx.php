<?php
namespace app\modules\v1\admin\models\posts;

use app\models\post\PostStatus;

/**
 * Class PostStatusEx
 *
 * @package app\modules\v1\admin\models\posts
 *
 * @SWG\Definition(
 *     definition = "PostStatusList",
 *     type = "array",
 *     @SWG\Items( ref = "#/definitions/PostStatus" )
 * )
 */
class PostStatusEx extends PostStatus
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostStatus",
	 *       required   = { "name" },
	 *
	 *     @SWG\Property( property = "id", type = "integer" ),
	 *     @SWG\Property( property = "name", type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [ "id", "name", ];
	}

	/**
	 * @return \app\models\post\PostStatusBase[]|array
	 */
	public static function getAllStatuses ()
	{
		return self::find()->asArray()->all();
	}
}
