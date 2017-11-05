<?php
namespace app\modules\v1\admin\models\user;

use app\models\user\User;

/**
 * Class UserEx
 *
 * @package app\modules\v1\admin\models\user
 *
 * @SWG\Definition(
 *     definition = "User",
 *
 *     @SWG\Property( property = "id", type = "integer" ),
 *     @SWG\Property( property = "username", type = "string" ),
 *     @SWG\Property( property = "auth_token", type = "string" ),
 *     @SWG\Property( property = "last_login", type = "string" ),
 *     @SWG\Property( property = "profile", ref = "#/definitions/UserProfile", ),
 * )
 */
class UserEx extends User
{
	public function getUserProfile () { return $this->hasOne(UserProfileEx::className(), [ "user_id" => "id" ]); }
	
	public function fields ()
	{
		return [
			"id",
			"username",
			"auth_token",
			"last_login",
			"profile" => "userProfile"
		];
	}
}
