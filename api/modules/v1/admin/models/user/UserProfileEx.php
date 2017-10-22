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
	
	public function fields ()
	{
		return [
			"firstname",
			"lastname",
			"fullname" => function ( self $model ) { return "$model->firstname $model->lastname"; },
			"birthday" => function ( self $model ) { return date(self::DATE_FORMAT, strtotime($model->birthday)); },
		];
	}
}
