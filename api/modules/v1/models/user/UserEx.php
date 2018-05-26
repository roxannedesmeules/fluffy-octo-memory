<?php

namespace app\modules\v1\models\user;

use app\models\user\User;

/**
 * Class UserEx
 *
 * @package app\modules\v1\models
 */
class UserEx extends User
{
	public function fields ()
	{
		return [
			"id",
			"fullname"  => function ( self $model ) { return $model->getFullname(); },
			"firstname" => function ( self $model ) { return $model->userProfile->firstname; },
			"lastname"  => function ( self $model ) { return $model->userProfile->lastname; },
			"picture"   => function ( self $model ) { return $model->userProfile->file->getFullPath(); },
		];
	}

	/**
	 * Return the user's full name.
	 *
	 * @return string
	 */
	public function getFullname ()
	{
		return "{$this->userProfile->firstname} {$this->userProfile->lastname}";
	}
}
