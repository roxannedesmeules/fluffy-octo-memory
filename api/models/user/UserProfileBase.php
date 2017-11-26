<?php

namespace app\models\user;

use app\models\app\File;
use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property int      $user_id
 * @property string   $firstname
 * @property string   $lastname
 * @property string   $birthday
 * @property int      $file_id
 *
 * @property UserBase $user
 * @property File     $file
 */
abstract class UserProfileBase extends \yii\db\ActiveRecord
{
	const DATE_FORMAT = "Y-m-d";

	const ERROR   = 0;
	const SUCCESS = 1;

	const ERR_ON_SAVE   = "ERR_ON_SAVE";
	const ERR_NOT_FOUND = "ERR_NOT_FOUND";

	/** @var array      list of extensions allowed */
	public static $extensions = [ "jpg", "jpeg", "gif", "png", ];

	/** @inheritdoc */
	public static function tableName () { return 'user_profile'; }
	
	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "birthday", "safe" ],

			[ "firstname", "string", "max" => 255 ],

			[ "lastname", "string", "max" => 255 ],

			[
				[ 'user_id' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => User::className(),
				'targetAttribute' => [ 'user_id' => 'id' ],
			],

			[ "file_id", "integer" ],
			[
				[ "file_id" ], "exist",
				"skipOnError"     => true,
				"targetClass"     => File::className(),
				"targetAttribute" => [ "file_id" => "id" ],
			],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'user_id'   => Yii::t('app.user', 'User ID'),
			'firstname' => Yii::t('app.user', 'First name'),
			'lastname'  => Yii::t('app.user', 'Last name'),
			'birthday'  => Yii::t('app.user', 'Birthday'),
			"file_id"   => Yii::t("app.user", "Picture ID"),
			"file"      => Yii::t("app.user", "User picture"),
		];
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getUser ()
	{
		return $this->hasOne(UserBase::className(), [ 'id' => 'user_id' ]);
	}

	/** @return \yii\db\ActiveQuery */
	public function getFile ()
	{
		return $this->hasOne(File::className(), [ "id" => "file_id" ]);
	}
	
	/**
	 * @inheritdoc
	 * @return UserProfileQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new UserProfileQuery(get_called_class());
	}

	/**
	 * Verify if a user exists with specific id
	 *
	 * @param $userId
	 *
	 * @return bool
	 */
	public static function exists ( $userId )
	{
		return self::find()->user($userId)->exists();
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
}
