<?php

namespace app\models\user;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int         $id
 * @property string      $username
 * @property string      $password_hash
 * @property string      $password_reset_token
 * @property string      $auth_token
 * @property int         $is_active
 * @property string      $created_on
 * @property string      $updated_on
 * @property string      $last_login
 *
 * Relations :
 * @property PostLang[]  $postLangs
 * @property UserProfile $userProfile
 */
abstract class UserBase extends \yii\db\ActiveRecord
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

    const ACTIVE   = 1;
    const INACTIVE = 0;

    /** @inheritdoc */
    public static function tableName() { return 'user'; }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ["username", "required"],
            ["username", "string", "max" => 255],
            ["username", "unique"],

            ["password_hash", "required"],
            ["password_hash", "string", "max" => 255],

            ["password_reset_token", "string", "max" => 5],
            ["password_reset_token", "unique"],

            ["auth_token", "required"],
            ["auth_token", "string", "max" => 32],
            ["auth_token", "unique"],

            ["is_active", "integer"],

            ["created_on", "required"],
            ["created_on", "safe"],

            ["updated_on", "safe"],
            ["last_login", "safe"],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('app.user', 'ID'),
            'username'             => Yii::t('app.user', 'Username'),
            'password_hash'        => Yii::t('app.user', 'Password Hash'),
            'password_reset_token' => Yii::t('app.user', 'Password Reset Token'),
            'auth_token'           => Yii::t('app.user', 'Auth Token'),
            'is_active'            => Yii::t('app.user', 'Is Active'),
            'created_on'           => Yii::t('app.user', 'Created On'),
            'updated_on'           => Yii::t('app.user', 'Updated On'),
            'last_login'           => Yii::t('app.user', 'Last Login'),
        ];
    }

    /** @return \yii\db\ActiveQuery */
    public function getPostLangs()
    {
        return $this->hasMany(PostLang::className(), ['user_id' => 'id']);
    }

    /** @return \yii\db\ActiveQuery */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        switch ($insert) {
            case true:
                $this->created_on = date(self::DATE_FORMAT);
                break;

            case false:
                $this->updated_on = date(self::DATE_FORMAT);
                break;
        }

        return true;
    }

    /**
     * Verify if a user exists with specific id
     *
     * @param $userId
     *
     * @return bool
     */
    public static function exists($userId)
    {
        return self::find()->id($userId)->exists();
    }
}
