<?php
namespace app\models\user;

use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models\user
 */
class User extends UserBase implements IdentityInterface
{
	public static function findIdentity ( $id )
	{
		return static::find()->id($id)->one();
	}
	
	public static function findIdentityByAccessToken ( $token, $type = null )
	{
		return static::find()->authToken($token)->one();
	}
	
	public function getAuthKey ()
	{
		return $this->auth_token;
	}
	
	public function getId ()
	{
		return $this->id;
	}
	
	private static function generateToken ()
	{
		do {
			$token    = \Yii::$app->getSecurity()->generateRandomString(32);
			$isUnique = empty(self::findIdentityByAccessToken($token));
		} while ( !$isUnique );
		
		return $token;
	}
	
	public function isActive () { return ($this->is_active === self::ACTIVE); }
	
	/**
	 * Save current time as the last login
	 */
	public function registerLogin ()
	{
		$this->last_login = date(self::DATE_FORMAT);
		$this->save();
	}
	
	public function resetToken ()
	{
		$this->auth_token = self::generateToken();
		$this->save();
	}

	/**
	 * This method will generate the hash from the password and save it.
	 *
	 * @param string $password
	 *
	 * @return bool
	 */
	public function updatePassword ( $password )
	{
		$this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($password);

		return $this->save();
	}
	
	public function validateAuthKey ( $authKey )
	{
		return ($this->auth_token === $authKey);
	}
}