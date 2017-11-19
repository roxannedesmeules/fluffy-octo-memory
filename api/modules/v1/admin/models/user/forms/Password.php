<?php
namespace app\modules\v1\admin\models\user\forms;

use app\modules\v1\admin\models\user\UserEx;
use yii\base\Model;

/**
 * Class Password
 *
 * @package app\modules\v1\admin\models\user\forms
 *
 * @SWG\Definition(
 *     definition = "PasswordForm",
 *     required   = { "current", "password", "confirmation" },
 *
 *     @SWG\Property( property = "current", type = "string", format = "password", ),
 *     @SWG\Property( property = "password", type = "string", format = "password" ),
 *     @SWG\Property( property = "confirmation", type = "string", format = "password" ),
 * )
 */
class Password extends Model
{
	const ERROR   = 0;
	const SUCCESS = 1;

	const ERR_PASSWORD     = "ERR_WRONG_PASSWORD";
	const ERR_CONFIRMATION = "ERR_CONFIRMATION_NOT_MATCH";
	const ERR_ON_SAVE      = "ERR_ON_PASSWORD_SAVE";

	/** @var  UserEx */
	protected $_user;

	/** @var  string */
	public $current;

	/** @var  string */
	public $password;

	/** @var  string */
	public $confirmation;

	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "current", "required" ],
			[ "current", "string" ],

			[ "password", "required" ],
			[ "password", "string" ],

			[ "confirmation", "required" ],
			[ "confirmation", "string" ],
		];
	}

	protected function oldPasswordMatch ()
	{
		return \Yii::$app->getSecurity()->validatePassword($this->password, $this->_user->password_hash);
	}

	protected function confirmationMatch ()
	{
		return ($this->password === $this->confirmation);
	}

	public function updatePassword ()
	{
		// find the user that changes her password
		$this->_user = UserEx::find()->id(\Yii::$app->getUser()->getId())->one();

		// compare the password with the one in the database, so no one tries to update it with a stale screen.
		if ( !$this->oldPasswordMatch() ) {
			return [ "status" => self::ERROR, "error" => self::ERR_PASSWORD ];
		}

		//  make sure the new password and the confirmation matches
		if ( !$this->confirmationMatch() ) {
			return [ "status" => self::ERROR, "error" => self::ERR_CONFIRMATION ];
		}

		// update the user passwords and return an error if couldn't be save
		if ( !$this->_user->updatePassword($this->password) ) {
			return [ "status" => self::ERROR, "error" => self::ERR_ON_SAVE ];
		}

		//  return success
		return [ "status" => self::SUCCESS ];
	}
}
