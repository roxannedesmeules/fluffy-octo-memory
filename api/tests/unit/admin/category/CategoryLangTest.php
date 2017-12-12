<?php
namespace app\tests\unit\admin\category;

use app\tests\_support\_fixtures\CategoryLangFixture;
use app\models\category\CategoryLang as Model;
use Faker\Factory as Faker;

/**
 * Class ModelTest
 *
 * @package admin\category
 *
 * @group   admin
 * @group   category
 */
class CategoryLangTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/** @var Model  @specify */
	protected $model;

	protected function _before ()
	{
		$this->faker = Faker::create();
	}

	protected function _after () { }

	public function _fixtures()
	{
		return [
			'categoryLang' => [
				'class' => CategoryLangFixture::className(),
			],
		];
	}

	public function testValidation ()
	{
		$this->model = new Model();

		$this->specify("lang_id is required", function () {
			$this->tester->assertFalse($this->model->validate([ "lang_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_REQUIRED, $this->model->getErrors("lang_id"));
		});

		$this->specify("lang_id is expected to be an integer", function () {
			$this->model->lang_id = "en";

			$this->tester->assertFalse($this->model->validate([ "lang_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_TYPE, $this->model->getErrors("lang_id"));
		});

		$this->specify("lang_id is expected to exists in table lang", function () {
			$this->model->lang_id = 1000;

			$this->tester->assertFalse($this->model->validate([ "lang_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_FOUND, $this->model->getErrors("lang_id"));
		});

		$this->specify("category_id is required", function () {
			$this->tester->assertFalse($this->model->validate([ "category_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_REQUIRED, $this->model->getErrors("category_id"));
		});
		$this->specify("category_id is expected to be an integer", function () {
			$this->model->category_id = "wrong-category";

			$this->tester->assertFalse($this->model->validate([ "category_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_TYPE, $this->model->getErrors("category_id"));
		});
		$this->specify("category_id is expected to exists in table category", function () {
			$this->model->category_id = 1000;

			$this->tester->assertFalse($this->model->validate([ "category_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_FOUND, $this->model->getErrors("category_id"));
		});

		$this->specify("name is required", function () {
			$this->tester->assertFalse($this->model->validate([ "name" ]));
			$this->tester->assertContains(Model::ERR_FIELD_REQUIRED, $this->model->getErrors("name"));
		});
		$this->specify("name is expected to be less than 255 characters", function () {
			$this->model->name = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->tester->assertFalse($this->model->validate([ "name" ]));
			$this->tester->assertContains(Model::ERR_FIELD_TOO_LONG, $this->model->getErrors("name"));
		});
		$this->specify("name is expected to be unique in a language", function () {
			$this->model->lang_id = 2;
			$this->model->name    = $this->tester->grabFixture("categoryLang", "inactive-fr")->name;

			$this->tester->assertFalse($this->model->validate([ "name" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_UNIQUE, $this->model->getErrors("name"));

			//  this name should work even though it exists, since it's for another language
			$this->model->lang_id = 1;

			$this->tester->assertTrue($this->model->validate([ "name" ]), "name should only be unique by language");
		});

		$this->specify("slug is required", function () {
			$this->tester->assertFalse($this->model->validate([ "slug" ]));
			$this->tester->assertContains(Model::ERR_FIELD_REQUIRED, $this->model->getErrors("slug"));
		});
		$this->specify("slug is expected to be less than 255 characters", function () {
			$this->model->slug = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->tester->assertFalse($this->model->validate([ "slug" ]));
			$this->tester->assertContains(Model::ERR_FIELD_TOO_LONG, $this->model->getErrors("slug"));
		});
		$this->specify("slug is expected to be unique in a language", function () {
			$this->model->lang_id = 2;
			$this->model->slug    = $this->tester->grabFixture("categoryLang", "inactive-fr")->slug;

			$this->tester->assertFalse($this->model->validate([ "slug" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_UNIQUE, $this->model->getErrors("slug"));

			//  this slug should work even though it exists, since it's for another language
			$this->model->lang_id = 1;

			$this->tester->assertTrue($this->model->validate([ "slug" ]), "slug should only be unique by language");
		});
	}


	public function testCreate ()
	{
		$this->model = new Model();

		$this->specify("not create with invalid category", function () {
			$this->model = CategoryLangFixture::build(1000, 2);

			$result      = Model::createTranslation($this->model[ "category_id" ], $this->model);

			$this->tester->assertTrue(($result[ "status" ] === Model::ERROR), "should not create with invalid category");
			$this->tester->assertTrue(($result[ "error" ] === Model::ERR_CATEGORY_NOT_FOUND), "should return ERR_CATEGORY_NOT_FOUND");
		});

		$this->specify("not create with invalid model", function () {
			$this->model = CategoryLangFixture::build(2, 1, false);

			$result      = Model::createTranslation($this->model[ "category_id" ], $this->model);

			$this->tester->assertTrue(($result[ "status" ] === Model::ERROR));
			$this->tester->assertTrue(is_array($result[ "error" ]));
		});

		$this->specify("not create an existing translation", function () {
			$this->model = CategoryLangFixture::build(1, 1);

			$result      = Model::createTranslation($this->model[ "category_id" ], $this->model);

			$this->tester->assertTrue(($result[ "status" ] === Model::ERROR), "should not create an existing translation");
			$this->tester->assertTrue(($result[ "error" ] === Model::ERR_TRANSLATION_EXISTS), "should return ERR_TRANSLATION_ALREADY_EXISTS");
		});

		$this->specify("create translation", function () {
			$this->model = CategoryLangFixture::build(2, 1);

			$result      = Model::createTranslation($this->model[ "category_id" ], $this->model);

			$this->tester->assertTrue(($result[ "status" ] === Model::SUCCESS));
		});
	}

	public function testUpdate ()
	{
		$this->model = $this->tester->grabFixture("categoryLang", "inactive-fr");

		$this->specify("not update a non-existing translation", function () {
			$result = Model::updateTranslation(1000, 1000, $this->model);

			$this->tester->assertTrue(($result[ "status" ] === Model::ERROR), "should not update non-existing translation");
			$this->tester->assertTrue(($result[ "error" ] === Model::ERR_NOT_FOUND), "should return ERR_NOT_FOUND");
		});

		$this->specify("not update an invalid translation", function () {
			$this->model->name = \Yii::$app->getSecurity()->generateRandomString(256);

			$result = Model::updateTranslation($this->model->category_id, $this->model->lang_id, $this->model);

			$this->tester->assertTrue(($result[ "status" ] === Model::ERROR), "should not update an invalid translation");
			$this->tester->assertTrue(is_array($result[ "error" ]), "should return list of errors for each fields");
		});

		$this->specify("update translation", function () {
			$this->model->name = $this->faker->text();

			$result = Model::updateTranslation($this->model->category_id, $this->model->lang_id, $this->model);

			$this->tester->assertTrue(($result[ "status" ] === Model::SUCCESS));
		});
	}

	public function testDelete () {}
}