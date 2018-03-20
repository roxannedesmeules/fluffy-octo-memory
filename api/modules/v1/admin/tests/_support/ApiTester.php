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

	public function wantToVerifyApiClientRequired ($action, $url)
	{
		$this->wantToBeAuthenticated();
		$this->{ "send$action" }( $url );

		$this->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);
		$this->seeResponseContainsJson([ "code" => 403, "message" => "MISSING_API_CLIENT_KEY" ]);
	}

	public function wantToVerifyAuthenticationRequired ($action, $url)
	{
		$this->wantToSetApiClient();
		$this->{ "send$action" }( $url );

		$this->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED);
		$this->seeResponseContainsJson([ "code" => 401, "message" => "Your request was made with invalid credentials." ]);
	}
}
