<?php

/**
 * Class LangCest
 */
class LangCest
{
	public $url = "/languages";

	public function _before ( ApiTester $I ) { }

	public function _after ( ApiTester $I ) { }

	public function getAllWithoutApiClient ( ApiTester $I )
	{
		$I->wantToVerifyApiClientRequired("GET", $this->url);
	}

	public function getAllWithoutBeingAuthenticated ( ApiTester $I )
	{
		$I->wantToVerifyAuthenticationRequired("GET", $this->url);
	}

	public function getAll ( ApiTester $I )
	{
		$I->wantToSetApiClient();
		$I->wantToBeAuthenticated();

		$I->sendGET($this->url);

		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
		$I->seeResponseMatchesJsonType([
				"id"     => "integer|string",
				"icu"    => "string",
				"name"   => "string",
				"native" => "string",
		]);
	}
}
