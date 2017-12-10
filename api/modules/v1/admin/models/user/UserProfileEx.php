<?php
namespace app\modules\v1\admin\models\user;

use app\models\user\UserProfile;

/**
 * Class UserProfileEx
 *
 * @package app\modules\v1\admin\models\user
 */
class UserProfileEx extends UserProfile
{
	const DATE_FORMAT = "Y-m-d";

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "UserProfile",
	 *
	 *     @SWG\Property( property = "firstname", type = "string" ),
	 *     @SWG\Property( property = "lastname", type = "string" ),
	 *     @SWG\Property( property = "fullname", type = "string" ),
	 *     @SWG\Property( property = "birthday", type = "string" ),
	 *     @SWG\Property( property = "picture", type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"firstname",
			"lastname",
			"fullname" => function ( self $model ) { return "$model->firstname $model->lastname"; },
			"birthday" => function ( self $model ) { return date(self::DATE_FORMAT, strtotime($model->birthday)); },
			"picture"  => function ( self $model ) { return $model->file->getFullPath(); },
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "UserProfileForm",
	 *
	 *     @SWG\Property( property = "firstname", type = "string", minLength = 2 ),
	 *     @SWG\Property( property = "lastname", type = "string", minLength = 2 ),
	 *     @SWG\Property( property = "birthday", type = "string", format = "date" ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "firstname", "string" ],
			[ "lastname", "string" ],
			[ "birthday", "string" ],
		];
	}
}
