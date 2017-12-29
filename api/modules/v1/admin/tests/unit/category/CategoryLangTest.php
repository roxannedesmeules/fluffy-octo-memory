<?php

namespace app\modules\v1\admin\tests\category;

use app\modules\v1\admin\models\category\CategoryLangEx as Model;
use app\modules\v1\admin\tests\_support\_fixtures\CategoryExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\CategoryLangExFixture;
use Faker\Factory as Faker;

/**
 * Class CategoryLangTest
 *
 * @package app\modules\v1\admin\tests\category
 *
 * @group admin
 * @group category
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

	/** @inheritdoc */
	protected function _before ()
	{
		$this->faker = Faker::create();

		$this->tester->haveFixtures([
			                            "categoryLang" => CategoryLangExFixture::className(),
			                            "category"     => CategoryExFixture::className(),
		                            ]);
	}

	/** @inheritdoc */
	protected function _after () { }

	public function testValidation ()
	{
		$this->model = new Model();

		$this->specify("lang_id is required", function () {
			$this->tester->assertFalse($this->model->validate([ "lang_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_REQUIRED, $this->model->getErrors("lang_id"));
		});
		$this->specify("lang_id is expected to exists in lang table", function () {
			$this->model->lang_id = 1000;

			$this->tester->assertFalse($this->model->validate([ "lang_id" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_FOUND, $this->model->getErrors("lang_id"));
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
		$this->specify("name is expected to be unique for the language", function () {
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
		$this->specify("slug is expected to be unique for the language", function () {
			$this->model->lang_id = 2;
			$this->model->slug    = $this->tester->grabFixture("categoryLang", "inactive-fr")->slug;

			$this->tester->assertFalse($this->model->validate([ "slug" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_UNIQUE, $this->model->getErrors("slug"));

			//  this name should work even though it exists, since it's for another language
			$this->model->lang_id = 1;

			$this->tester->assertTrue($this->model->validate([ "slug" ]), "name should only be unique by language");
		});

		$this->specify("valid model", function () {
			$this->model->lang_id = 2;
			$this->model->name    = $this->faker->text();
			$this->model->slug    = $this->faker->slug();

			$this->tester->assertTrue($this->model->validate());
		});
	}

	public function testManageTranslations ()
	{
		$this->specify("not create/update with invalid category", function () {
			$result     = Model::manageTranslations(1000, []);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Model::ERR_CATEGORY_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create/update if one model has invalid language", function () {
			$categoryId = $this->tester->grabFixture("category", "active")->id;
			$models     = [
				[ "lang_id" => 1000, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$result = Model::manageTranslations($categoryId, $models);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Model::ERR_LANG_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create/update if one model is invalid", function () {
			$categoryId = $this->tester->grabFixture("category", "active")->id;
			$models     = [
				$this->tester->grabFixture("categoryLang", "active-fr"),
				[ "lang_id" => 1, "slug" => "test" ],
			];

			$result = Model::manageTranslations($categoryId, $models);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertTrue(is_array($result[ "error" ]));
		});

		$this->specify("create one model, then update it if same language", function () {
			$categoryId = $this->tester->grabFixture("category", "nolang")->id;
			$models     = [
				[ "lang_id" => 1, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => 1, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$result = Model::manageTranslations($categoryId, $models);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->seeNumRecords(1, Model::tableName(), [ "category_id" => $categoryId ]);
		});
		$this->specify("create both model in list", function () {
			$categoryId = $this->tester->grabFixture("category", "nolang")->id;
			$models     = [
				[ "lang_id" => 1, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => 2, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$result = Model::manageTranslations($categoryId, $models);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->seeNumRecords(2, Model::tableName(), [ "category_id" => $categoryId ]);
		});
		$this->specify("create one and update one model in list", function () {
			$categoryId = $this->tester->grabFixture("category", "active")->id;
			$models     = [
				$this->tester->grabFixture("categoryLang", "active-fr"),
				[ "lang_id" => 1, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$result = Model::manageTranslations($categoryId, $models);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->seeNumRecords(2, Model::tableName(), [ "category_id" => $categoryId ]);
		});
		$this->specify("update both model in list", function () {
			$categoryId = $this->tester->grabFixture("category", "inactive")->id;
			$models     = [
				$this->tester->grabFixture("categoryLang", "inactive-en"),
				$this->tester->grabFixture("categoryLang", "inactive-fr"),
			];

			$result = Model::manageTranslations($categoryId, $models);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->seeNumRecords(2, Model::tableName(), [ "category_id" => $categoryId ]);
		});
	}
}