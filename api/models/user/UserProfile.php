<?php
namespace app\models\user;

use app\helpers\ArrayHelperEx;
use app\models\app\File;


/**
 * Class UserProfile
 *
 * @package app\models\user
 */
class UserProfile extends UserProfileBase
{
	/**
	 * Verify if the profile has a picture
	 *
	 * @return bool
	 */
	public function hasPicture () { return !is_null($this->file_id); }

	/**
	 * @param integer $userId
	 * @param array   $data
	 *
	 * @return array
	 */
	public static function updateProfile ( $userId, $data )
	{
		//  check if a user profile with this ID exists
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

	/**
	 * @param $userId
	 * @param $file
	 *
	 * @return array
	 * @throws \yii\base\Exception
	 */
	public static function uploadPicture ( $userId, $file )
	{
		//  check if a user profile with this ID exists
		if (!self::exists($userId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  upload the profile picture
		$result = File::uploadProfileLocally($file);

		//  in case of error, return it
		if (is_string($result)) {
			return self::buildError($result);
		}

		//  find the user profile to update
		$model = self::find()->user($userId)->one();

		//  if there is already a profile picture, then mark the file entry as deleted
		if ($model->hasPicture()) {
			$model->file->markAsDeleted();
		}

		//  update the picture file id
		$model->file_id = $result;

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