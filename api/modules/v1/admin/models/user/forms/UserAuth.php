<?php
namespace app\modules\v1\admin\models\user\forms;

use app\modules\v1\admin\models\user\UserEx;
use yii\base\Model;

/**
 * Class UserAuth
 *
 * @package app\modules\v1\admin\models\user\forms
 *          
 * @SWG\Definition(
 *     definition = "UserAuth",
 *     required   = { "username", "password" },
 *
 *     @SWG\Property( property = "username", type = "string" ),
 *     @SWG\Property( property = "password", type = "string" ),
 * )
 */
class UserAuth extends Model
{
	const ERROR   = 0;
	const SUCCESS = 1;
	
	const ERR_USERNAME_PASSWORD = "ERR_WRONG_USERNAME_PASSWORD";
	const ERR_USER_INACTIVE     = "ERR_USER_INACTIVE";
	
	/** @var  UserEx */
	private $_user;
	
	public $username;
	public $password;

	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "username", "required" ],
			[ "username", "string" ],

			[ "password", "required" ],
			[ "password", "string" ],
		];
	}
	
	public function usernameExists ()
	{
		return UserEx::find()->username($this->username)->exists();
	}
	
	public function passwordMatch ()
	{
		return \Yii::$app->getSecurity()->validatePassword($this->password, $this->_user->password_hash);
	}
	
	public function authenticate ()
	{
		//  if username doesn't exists, then return error
		if ( !$this->usernameExists() ) {
			return [ "status" => self::ERROR, "error" => self::ERR_USERNAME_PASSWORD ];
		}
		
		//  find user for this username
		$this->_user = UserEx::find()->username($this->username)->one();
		
		//  if password doesn't match, then return error
		if ( !$this->passwordMatch() ) {
			return [ "status" => self::ERROR, "error" => self::ERR_USERNAME_PASSWORD ];
		}
		
		//  if the user isn't active, then return error
		if (!$this->_user->isActive()) {
			return [ "status" => self::ERROR, "error" => self::ERR_USER_INACTIVE ];
		}
		
		//  register last login for this user
		$this->_user->registerLogin();
		
		//  return success with user info
		return [ "status" => self::SUCCESS, "user" => $this->_user->toArray() ];
	}
	
	public function logout ( $userId )
	{
		//  find user for this id
		$this->_user = UserEx::find()->id($userId)->one();
		
		//  reset the auth token to invalidate the session
		$this->_user->resetToken();
		
		return [ "status" => self::SUCCESS ];
	}
}
