<?php

namespace app\modules\v1\admin\models\user;

use app\components\validators\ArrayUniqueValidator;
use app\components\validators\TranslationValidator;
use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;
use app\models\user\UserProfile;

/**
 * Class UserProfileEx
 *
 * @package app\modules\v1\admin\models\user
 *
 * @property array $translations
 */
class UserProfileEx extends UserProfile
{
	/** @var array */
	public $translations = [];

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
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/UserProfileTranslation" ) ),
	 *     @SWG\Property( property = "picture", type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"firstname",
			"lastname",
			"fullname"     => function ( self $model ) { return $model->getFullname(); },
			"birthday"     => function ( self $model ) { return date(DateHelper::DATE_FORMAT, strtotime($model->birthday)); },
			"translations" => "profileLangs",
			"picture"      => function ( self $model ) { return ($model->file) ? $model->file->getFullPath() : null; },
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
	 *     
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/ProfileTranslationForm" ) ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "firstname", "string" ],
			[ "lastname", "string" ],
			[ "birthday", "string" ],

			[ "translations", TranslationValidator::className(), "validator" => UserProfileLangEx::className() ],
			[ "translations", ArrayUniqueValidator::className(), "uniqueKey" => "lang_id" ],
		];
	}

	/** @inheritdoc */
	public function getProfileLangs () { return $this->hasMany(UserProfileLangEx::className(), [ "user_id" => "user_id" ]); }

	/**
	 * Return the user's full name.
	 *
	 * @return string
	 */
	public function getFullname ()
	{
		return "$this->firstname $this->lastname";
	}

	/**
	 * @param integer             $userId
	 * @param array|self          $profile
	 * @param UserProfileLangEx[] $translations
	 *
	 * @return array
	 * @throws \yii\db\Exception
	 */
	public static function updateProfileWithTranslation ( $userId, $profile, $translations )
	{
		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		//  update the user profile entry
		$result = self::updateProfile($userId, $profile);

		//  in case of error, rollback and return error
		if ($result[ "status" ] === self::ERROR) {
			$transaction->rollBack();

			return $result;
		}

		//  update all translations
		$result = UserProfileLangEx::manageTranslations($userId, $translations);

		//  in case of error, rollback and return it
		if (in_array(UserProfileLangEx::ERROR, ArrayHelperEx::getColumn($result, "status"))) {
			$transaction->rollBack();

			return self::buildError([
				"translations" => ArrayHelperEx::filterInArrayAtIndex(UserProfileLangEx::ERROR, $result, "status"),
			]);
		}

		//  commit all changes made to database
		$transaction->commit();

		//  return success
		return self::buildSuccess([]);
	}
}
