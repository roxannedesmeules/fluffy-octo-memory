<?php

namespace app\models\user;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property int      $user_id
 * @property string   $firstname
 * @property string   $lastname
 * @property string   $birthday
 *
 * @property UserBase $user
 */
abstract class UserProfileBase extends \yii\db\ActiveRecord
{
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
				'targetClass'     => UserBase::className(),
				'targetAttribute' => [ 'user_id' => 'id' ],
			],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'user_id'   => Yii::t('app.user', 'User ID'),
			'firstname' => Yii::t('app.user', 'Firstname'),
			'lastname'  => Yii::t('app.user', 'Lastname'),
			'birthday'  => Yii::t('app.user', 'Birthday'),
		];
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getUser ()
	{
		return $this->hasOne(UserBase::className(), [ 'id' => 'user_id' ]);
	}
	
	/**
	 * @inheritdoc
	 * @return UserProfileQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new UserProfileQuery(get_called_class());
	}
}
