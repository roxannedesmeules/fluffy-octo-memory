<?php
namespace app\models\user;
use app\helpers\ArrayHelperEx;


/**
 * Class UserProfile
 *
 * @package app\models\user
 */
class UserProfile extends UserProfileBase
{
	public static function updateProfile ( $userId, $data )
	{
		//  check if a user with this ID exists
		if (!self::exists($userId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		// find the profile entry to update
		$model = self::find()->user($userId)->one();

		$model->firstname = ArrayHelperEx::getValue($data, "firstname", $model->firstname);
		$model->lastname  = ArrayHelperEx::getValue($data, "lastname", $model->lastname);
		$model->birthday  = ArrayHelperEx::getValue($data, "birthday", $model->birthday);

		//  if the model doesn't validate, then return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}

		//  if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		//  return success
		return self::buildSuccess([]);
	}
}