<?php

/**
 * Class CategoryCest
 *
 * @group category
 */
class CategoryCest
{
	public $url = "/categories";

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
			"id" => "integer",
			"is_active" => "integer:boolean",
			"translations" => [
				[
					"lang_id" => "integer",
					"name" => "string",
				]
			],
		]);
	}

	public function getOneWithoutApiClient ( ApiTester $I )
	{
		$I->wantToVerifyApiClientRequired("GET", "$this->url/1");
	}

	public function getOneWithoutBeingAuthenticated ( ApiTester $I )
	{
		$I->wantToVerifyAuthenticationRequired("GET", "$this->url/1");
	}
	/** @skip */
	public function getOneWithInvalidId ( ApiTester $I ) { }
	/** @skip */
	public function getOne ( ApiTester $I ) { }

	/** @skip */
	public function createWithoutApiClient ( ApiTester $I ) {}
	/** @skip */
	public function createWithoutBeingAuthenticated ( ApiTester $I ) {}
	/** @skip */
	public function createWithInvalidModel ( ApiTester $I ) {}
	/** @skip */
	public function create ( ApiTester $I ) {}

	/** @skip */
	public function updateWithoutApiClient ( ApiTester $I ) {}
	/** @skip */
	public function updateWithoutBeingAuthenticated ( ApiTester $I ) {}
	/** @skip */
	public function updateWithInvalidModel ( ApiTester $I ) {}
	/** @skip */
	public function updateWithInvalidId ( ApiTester $I ) {}
	/** @skip */
	public function update ( ApiTester $I ) {}

	/** @skip */
	public function deleteWithoutApiClient ( ApiTester $I ) {}
	/** @skip */
	public function deleteWithoutBeingAuthenticated ( ApiTester $I ) {}
	/** @skip */
	public function deleteWithInvalidModel ( ApiTester $I ) {}
	/** @skip */
	public function deleteWithInvalidId ( ApiTester $I ) {}
	/** @skip */
	public function delete ( ApiTester $I ) {}
}
