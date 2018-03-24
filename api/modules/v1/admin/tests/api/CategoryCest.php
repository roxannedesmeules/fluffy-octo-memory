<?php

use \yii\helpers\Json;
use \Codeception\Util\HttpCode;
use \Codeception\Util\JsonType;

use \app\modules\v1\admin\models\LangEx;

/**
 * Class CategoryCest
 *
 * @group category
 */
class CategoryCest
{
	/** @var string  */
	public $url      = "/categories";

	/** @var array  */
	public $category = [
		"id"           => "integer",
		"is_active"    => "integer",
		"created_on"   => "null|string",
		"updated_on"   => "null|string",
		"translations" => [
			[
				"language" => "string",
				"name"     => "string",
				"slug"     => "string",
			],
		],
	];

	public function _before ( ApiTester $I )
	{
		$I->haveFixtures([
			"category"    => \app\modules\v1\admin\tests\_support\_fixtures\CategoryExFixture::className(),
			"translation" => \app\modules\v1\admin\tests\_support\_fixtures\CategoryLangExFixture::className(),
		]);
	}

	public function _after ( ApiTester $I ) { }

	/*
	 *  Get all categories
	 */

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

		$I->seeResponseCodeIs(HttpCode::OK);

		(new JsonType(Json::decode($I->grabResponse())))->matches($this->category);
	}

	/*
	 *  Get one category by ID
	 */

	public function getOneWithoutApiClient ( ApiTester $I )
	{
		$I->wantToVerifyApiClientRequired("GET", "$this->url/1");
	}

	public function getOneWithoutBeingAuthenticated ( ApiTester $I )
	{
		$I->wantToVerifyAuthenticationRequired("GET", "$this->url/1");
	}

	public function getOneWithInvalidId ( ApiTester $I )
	{
		$I->wantToSetApiClient();
		$I->wantToBeAuthenticated();

		$I->sendGET("$this->url/1000");

		$I->seeResponseCodeIs(HttpCode::NOT_FOUND);
		$I->seeResponseContainsJson([ "code" => HttpCode::NOT_FOUND, "message" => "ERR_NOT_FOUND" ]);
	}

	public function getOne ( ApiTester $I )
	{
		$I->wantToSetApiClient();
		$I->wantToBeAuthenticated();

		/** @var \app\modules\v1\admin\models\category\CategoryEx $category */
		$category = $I->grabFixture("category", "inactive");
		$category->translations = [
			$I->grabFixture("translation", "inactive-en"),
			$I->grabFixture("translation", "inactive-fr"),
		];

		$I->sendGET("$this->url/$category->id");

		$I->seeResponseCodeIs(HttpCode::OK);
		$I->seeResponseMatchesJsonType($this->category);
		$I->seeResponseContainsJson($category->toArray());
	}

	/*
	 *  Create one category
	 */

	public function createWithoutApiClient ( ApiTester $I )
	{
		$I->wantToVerifyApiClientRequired("POST", "$this->url");
	}

	public function createWithoutBeingAuthenticated ( ApiTester $I )
	{
		$I->wantToVerifyAuthenticationRequired("POST", $this->url);
	}

	public function createWithInvalidModel ( ApiTester $I )
	{
		$I->wantToSetApiClient();
		$I->wantToBeAuthenticated();

		$body = [
			"is_active"    => 1,
			"translations" => [
				[ "lang_id" => LangEx::EN, "name" => "test" ],
			],
		];

		$I->sendPOST($this->url, $body);

		$I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
		$I->seeResponseMatchesJsonType([
			"code"  => "integer",
			"error" => [
				"translations" => [ [ "slug" => [ "string" ] ], ],
			],
		]);
	}

	public function create ( ApiTester $I )
	{
		$I->wantToSetApiClient();
		$I->wantToBeAuthenticated();

		$body = [
			"is_active"    => 1,
			"translations" => [
				[ "lang_id" => LangEx::EN, "name" => "test", "slug" => "test" ],
				[ "lang_id" => LangEx::FR, "name" => "test", "slug" => "test" ],
			],
		];

		$I->sendPOST($this->url, $body);

		$I->seeResponseCodeIs(HttpCode::CREATED);
		$I->seeResponseMatchesJsonType([ "category_id" => "integer" ]);
	}

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
