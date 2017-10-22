<?php
namespace app\modules\v1\admin\models\user;

use app\models\user\User;

/**
 * Class UserEx
 *
 * @package app\modules\v1\admin\models\user
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
