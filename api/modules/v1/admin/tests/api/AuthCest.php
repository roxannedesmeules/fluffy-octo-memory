<?php

/**
 * Class AuthCest
 */
class AuthCest
{
	public $url = "/auth";

	public function _before ( ApiTester $I ) { }

	public function _after ( ApiTester $I ) { }

	/**
	 * @param \ApiTester $I
	 */
	public function loginWithoutApiClient ( ApiTester $I )
	{
		$I->sendPOST($this->url, [ "username" => "mlleDesmeules", "password" => "AAAaaa111" ]);

		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);
		$I->seeResponseContainsJson([ "code" => 403, "message" => "MISSING_API_CLIENT_KEY" ]);
	}

	/**
	 * @param \ApiTester $I
	 */
	public function loginWithInvalidApiClient ( ApiTester $I )
	{
		$I->haveHttpHeader("API-CLIENT", "invalid");
		$I->sendPOST($this->url, [ "username" => "mlleDesmeules", "password" => "AAAaaa111" ]);

		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);
		$I->seeResponseContainsJson([ "code" => 403, "message" => "INVALID_API_CLIENT_KEY" ]);
	}

	/**
	 * @param \ApiTester           $I
	 * @param \Codeception\Example $example
	 *
	 * @example { "username" : "invalid", "password" : "invalid" }
	 * @example { "username" : "mlleDesmeule", "password" : "AAAaaa111" }
	 * @example { "username" : "mlleDesmeules", "password" : "invalid" }
	 */
	public function loginWithInvalidCredentials ( ApiTester $I, \Codeception\Example $example )
	{
		$I->wantToSetApiClient();
		$I->sendPOST($this->url, [ "username" => $example[ "username" ], "password" => $example[ "password" ] ]);

		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
		$I->seeResponseContainsJson([ "code" => 400, "message" => "ERR_WRONG_USERNAME_PASSWORD" ]);
	}

	/**
	 * @param \ApiTester           $I
	 * @param \Codeception\Example $example

	 * @example { "username" : "mlleDesmeules", "password" : "AAAaaa111" }
	 */
	public function loginWithValidCredentials ( ApiTester $I, \Codeception\Example $example )
	{
		$I->wantToSetApiClient();
		$I->sendPOST($this->url, [ "username" => $example[ "username" ], "password" => $example[ "password" ] ]);

		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
		$I->seeResponseMatchesJsonType([
			"id"         => "integer",
			"username"   => "string",
			"auth_token" => "string",
			"last_login" => "string",
			"profile"    => [
				"firstname" => "string",
				"lastname"  => "string",
				"fullname"  => "string",
				"birthday"  => "string",
				"picture"   => "string|null",
			],
		]);
	}

	public function logout ( ApiTester $I )
	{
		$I->wantToSetApiClient();
		$I->wantToBeAuthenticated();

		$I->sendDELETE($this->url);

		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
	}
}
