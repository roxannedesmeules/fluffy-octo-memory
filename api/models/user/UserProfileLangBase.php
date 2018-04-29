<?php
namespace app\models\user;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;


/**
 * This is the model class for table "{{%user_profile_lang}}".
 *
 * @property int    $user_id
 * @property int    $lang_id
 * @property string $biography
 * @property string $job_title
 *
 * @property Lang   $lang
 * @property User   $user
 * @property UserProfile $profile
 */
abstract class UserProfileLangBase extends \yii\db\ActiveRecord
{
	const ERROR   = 0;
	const SUCCESS = 1;

	const ERR_ON_SAVE            = "ERR_ON_SAVE";
	const ERR_ON_DELETE          = "ERR_ON_DELETE";
	const ERR_NOT_FOUND          = "ERR_NOT_FOUND";
	const ERR_LANG_NOT_FOUND     = "ERR_LANG_NOT_FOUND";
	const ERR_TRANSLATION_EXISTS = "ERR_TRANSLATION_ALREADY_EXISTS";

	/** @inheritdoc */
	public static function tableName () { return '{{%user_profile_lang}}'; }

	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ [ 'user_id', 'lang_id' ], 'required' ],
			[ [ 'user_id', 'lang_id' ], 'integer' ],
			[ [ 'biography' ], 'string' ],
			[ [ 'job_title' ], 'string', 'max' => 255 ],
			[ [ 'user_id', 'lang_id' ], 'unique', 'targetAttribute' => [ 'user_id', 'lang_id' ] ],
			[
				[ 'lang_id' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Lang::className(),
				'targetAttribute' => [ 'lang_id' => 'id' ],
			],
			[
				[ 'user_id' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => User::className(),
				'targetAttribute' => [ 'user_id' => 'id' ],
			],
		];
	}

	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'user_id'   => 'User ID',
			'lang_id'   => 'Lang ID',
			'biography' => 'Biography',
			'job_title' => 'Job Title',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLang ()
	{
		return $this->hasOne(Lang::className(), [ 'id' => 'lang_id' ]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser ()
	{
		return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProfile ()
	{
		return $this->hasOne(UserProfile::className(), [ "user_id" => "user_id" ]);
	}

	/**
	 * @inheritdoc
	 * @return UserProfileLangQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new UserProfileLangQuery(get_called_class());
	}

	/**
	 * Build an array to use when returning from another method. The status will automatically
	 * set to ERROR, then $error passed in param will be associated to the error key.
	 *
	 * @param $error
	 *
	 * @return array
	 */
	public static function buildError ( $error )
	{
		return [ "status" => self::ERROR, "error" => $error ];
	}

	/**
	 * Build an array to use when returning from another method. The status will be automatically
	 * set to SUCCESS, then the $params will be merged with the array and be returned.
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public static function buildSuccess ( $params )
	{
		return ArrayHelperEx::merge([ "status" => self::SUCCESS ], $params);
	}

	public static function translationExists ( $userId, $langId )
	{
		return self::find()->byUser($userId)->byLang($langId)->exists();
	}
}
