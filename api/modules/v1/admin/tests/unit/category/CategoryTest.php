<?php
namespace app\modules\v1\admin\tests\category;

use app\models\app\Lang;
use app\modules\v1\admin\models\category\CategoryEx;
use app\modules\v1\admin\models\category\CategoryLangEx;
use app\modules\v1\admin\tests\_support\_fixtures\CategoryExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\CategoryLangExFixture;
use Faker\Factory as Faker;

/**
 * Class CategoryTest
 *
 * @package app\modules\v1\admin\tests\category
 *
 * @group   category
 */
class CategoryTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/** @var CategoryEx  @specify */
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
		$this->model = new CategoryEx();

		$this->specify("is_active is expected to be an integer", function () {
			$this->model->is_active = false;

			$this->tester->assertFalse($this->model->validate([ "is_active" ]));
			$this->tester->assertContains(CategoryEx::ERR_FIELD_TYPE, $this->model->getErrors("is_active"));
		});

		$this->specify("each translations should have a different language", function () {
			$this->model->translations = [
				[ "lang_id" => Lang::FR, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
				[ "lang_id" => Lang::FR, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
			];
			$this->tester->assertFalse($this->model->validate([ "translations" ]));
			$this->tester->assertContains(CategoryEx::ERR_FIELD_UNIQUE_LANG, $this->model->getErrors("translations"));
		});
		$this->specify("each translations should be a valid CategoryLangEx model", function () {
			$this->model->translations = [
				[ "lang_id" => Lang::FR, "name" => $this->faker->text() ],
				[ "lang_id" => Lang::EN, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
			];

			$this->tester->assertFalse($this->model->validate([ "translations" ]));

			$errors = $this->model->getErrors("translations");

			$this->tester->assertCount(1, $errors);
			$this->tester->assertArrayHasKey("slug", $errors[ 0 ]);
		});

		$this->specify("expected to be valid", function () {
			$this->model->translations = [];

			$this->tester->assertTrue($this->model->validate(), "is_active not set, translations empty");

			$this->model->is_active    = CategoryEx::ACTIVE;
			$this->model->translations = [
				[ "lang_id" => Lang::EN, "name" => $this->faker->text, "slug" => $this->faker->slug() ],
			];

			$this->tester->assertTrue($this->model->validate(), "is_active set, 1 translation");
		});
	}

	public function testCreateWithTranslation ()
	{
		$this->model = new CategoryEx();

		$this->specify("not create invalid model", function () {
			$this->model->is_active    = false;
			$this->model->translations = [
				[ "lang_id" => Lang::EN, "name" => $this->faker->text(), "slug" => $this->faker->slug(), ]
			];

			$result = CategoryEx::createWithTranslations($this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("is_active", $result[ "error" ]);
		});

		$this->specify("not create invalid translation", function () {
			$this->model->is_active    = CategoryEx::INACTIVE;
			$this->model->translations = [
				[ "lang_id" => Lang::EN, "name" => "invalid" ],
			];

			$result = CategoryEx::createWithTranslations($this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("translations", $result[ "error" ]);
			$this->tester->assertArrayHasKey("slug", $result[ "error" ][ "translations" ]);
		});

		$this->specify("create with empty translation", function () {
			$this->model->is_active    = CategoryEx::INACTIVE;
			$this->model->translations = [ ];

			$result = CategoryEx::createWithTranslations($this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("category_id", $result);

			$this->tester->canSeeInDatabase(CategoryEx::tableName(), [ "id" => $result[ "category_id" ] ]);
			$this->tester->cantSeeInDatabase(CategoryLangEx::tableName(), [ "category_id" => $result[ "category_id" ] ]);
		});
		$this->specify("create with translations", function () {
			$this->model->is_active    = CategoryEx::ACTIVE;
			$this->model->translations = [
				[ "lang_id" => Lang::FR, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => Lang::EN, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$result = CategoryEx::createWithTranslations($this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("category_id", $result);

			$this->tester->canSeeInDatabase(CategoryEx::tableName(), [ "id" => $result[ "category_id" ] ]);
			$this->tester->canSeeNumRecords(2, CategoryLangEx::tableName(), [ "category_id" => $result[ "category_id" ] ]);
		});
	}

	public function testUpdateWithTranslation ()
	{
		/** @var CategoryEx model */
		$this->model = $this->tester->grabFixture("category", "active");

		$this->specify("not update invalid category id", function () {
			$this->model->translations = [];

			$result = CategoryEx::updateWithTranslations(1000, $this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(CategoryEx::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not update invalid category model", function () {
			$this->model->is_active    = false;
			$this->model->translations = [];

			$result = CategoryEx::updateWithTranslations($this->model->id, $this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("is_active", $result[ "error" ]);
		});
		$this->specify("not update invalid translation model", function () {
			$this->model->is_active    = CategoryEx::ACTIVE;
			$this->model->translations = [
				[ "lang_id" => Lang::EN, "name" => $this->faker->text(), ],
			];

			$result = CategoryEx::updateWithTranslations($this->model->id, $this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("translations", $result[ "error" ]);
			$this->tester->assertArrayHasKey("slug", $result[ "error" ][ "translations" ]);
		});

		$this->specify("update category and create 2 translations", function () {
			$this->model = $this->tester->grabFixture("category", "nolang");

			$this->model->translations = [
				[ "lang_id" => Lang::FR, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => Lang::EN, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$this->tester->canSeeNumRecords(0, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);

			$result = CategoryEx::updateWithTranslations($this->model->id, $this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("category", $result);

			$this->tester->canSeeNumRecords(2, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);
		});
		$this->specify("update category and create 1 translation, update 1 translations", function () {
			$this->model->translations = [
				[ "lang_id" => Lang::FR, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => Lang::EN, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$this->tester->canSeeNumRecords(1, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);

			$result = CategoryEx::updateWithTranslations($this->model->id, $this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("category", $result);

			$this->tester->canSeeNumRecords(2, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);
		});
		$this->specify("update category and update 2 translations", function () {
			$this->model = $this->tester->grabFixture("category", "inactive");

			$this->model->translations = [
				[ "lang_id" => Lang::FR, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => Lang::EN, "name" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$this->tester->canSeeNumRecords(2, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);

			$result = CategoryEx::updateWithTranslations($this->model->id, $this->model, $this->model->translations);

			$this->tester->assertEquals(CategoryEx::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("category", $result);

			$this->tester->canSeeNumRecords(2, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);
		});
	}

	public function testDeleteWithTranslation ()
	{
		$this->specify("not delete an invalid category ID", function () {
			$result = CategoryEx::deleteWithTranslations(1000);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(CategoryEx::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not delete an active category", function () {
			$model  = $this->tester->grabFixture("category", "nopostactive");
			$result = CategoryEx::deleteWithTranslations($model->id);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(CategoryEx::ERR_DELETE_ACTIVE, $result[ "error" ]);
		});
		$this->specify("not delete a category with associated post", function () {
			$model  = $this->tester->grabFixture("category", "inactive");
			$result = CategoryEx::deleteWithTranslations($model->id);

			$this->tester->assertEquals(CategoryEx::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(CategoryEx::ERR_DELETE_POSTS, $result[ "error" ]);
		});
		$this->specify("delete category with all translations", function () {
			$this->model = $this->tester->grabFixture("category", "nopost");

			$this->tester->canSeeNumRecords(1, CategoryEx::tableName(), [ "id" => $this->model->id ]);
			$this->tester->canSeeNumRecords(1, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);

			$result = CategoryEx::deleteWithTranslations($this->model->id);

			$this->tester->assertEquals(CategoryEx::SUCCESS, $result[ "status" ]);

			$this->tester->canSeeNumRecords(0, CategoryEx::tableName(), [ "id" => $this->model->id ]);
			$this->tester->canSeeNumRecords(0, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);
		});
		$this->specify("delete category with no translations", function () {
			$this->model = $this->tester->grabFixture("category", "nopostlang");

			$this->tester->canSeeNumRecords(1, CategoryEx::tableName(), [ "id" => $this->model->id ]);
			$this->tester->canSeeNumRecords(0, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);

			$result = CategoryEx::deleteWithTranslations($this->model->id);

			$this->tester->assertEquals(CategoryEx::SUCCESS, $result[ "status" ]);

			$this->tester->canSeeNumRecords(0, CategoryEx::tableName(), [ "id" => $this->model->id ]);
			$this->tester->canSeeNumRecords(0, CategoryLangEx::tableName(), [ "category_id" => $this->model->id ]);
		});
	}
}