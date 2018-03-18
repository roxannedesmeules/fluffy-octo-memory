<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
	use _generated\ApiTesterActions;

	/**
	 *
	 */
	public function wantToSetApiClient ()
	{
		$token = $this->grabFromDatabase("api_client", "`key`", [ "name" => "Admin" ]);

		$this->haveHttpHeader("API-CLIENT", $token);
	}

	/**
	 *
	 */
	public function wantToBeAuthenticated ()
	{
		$token = $this->grabFromDatabase("user", "auth_token", [ "username" => "mlleDesmeules" ]);

		$this->amHttpAuthenticated($token, null);
	}
}
