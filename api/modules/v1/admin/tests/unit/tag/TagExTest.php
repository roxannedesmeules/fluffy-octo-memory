<?php

namespace app\modules\v1\admin\tests\unit\tag;

use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\tag\TagEx as Model;
use app\modules\v1\admin\models\tag\TagLangEx;
use app\modules\v1\admin\tests\_support\_fixtures\AssoTagPostExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\TagExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\TagLangExFixture;
use Faker\Factory as Faker;

/**
 * Class TagExTest
 *
 * @package app\modules\v1\admin\tests\unit\tag
 * @group   tag
 */
class TagExTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/** @var Model @specify */
	protected $model;

	protected function _before ()
	{
		$this->faker = Faker::create();

		$this->tester->haveFixtures([
			"asso"    => AssoTagPostExFixture::className(),
			"tagLang" => TagLangExFixture::className(),
			"tag"     => TagExFixture::className(),
		]);
	}

	protected function _after () { }

	// tests
	public function testValidation ()
	{
		$this->model = new Model();

		$this->specify("each translations should have a different language", function () {
			$this->model->translations = [
				[ "lang_id" => LangEx::FR, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
				[ "lang_id" => LangEx::FR, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
			];

			$this->tester->assertFalse($this->model->validate([ "translations" ]));
			$this->tester->assertContains(Model::ERR_FIELD_UNIQUE_LANG, $this->model->getErrors("translations"));
		});
		$this->specify("each translations should be a valid TagLangEx model", function () {
			$this->model->translations = [
				[ "lang_id" => LangEx::FR, "name" => $this->faker->text() ],
				[ "lang_id" => LangEx::EN, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
			];

			$this->tester->assertFalse($this->model->validate([ "translations" ]));

			$errors = $this->model->getErrors("translations");

			$this->tester->assertCount(1, $errors);
			$this->tester->assertArrayHasKey("slug", $errors[ 0 ]);
		});

		$this->specify("valid model", function () {
			$this->model->translations = [];

			$this->tester->assertTrue($this->model->validate(), "translations empty");

			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "name" => $this->faker->text, "slug" => $this->faker->slug() ],
			];

			$this->tester->assertTrue($this->model->validate(), "1 translation");
		});
	}

	public function testCreateWithTranslations ()
	{
		$this->model = new Model();

		$this->specify("not create with invalid translations", function () {
			$this->model->translations = [
				[ "lang_id" => LangEx::FR, "name" => $this->faker->text() ],
				[ "lang_id" => LangEx::EN, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
			];

			$result = Model::createWithTranslations($this->model->translations);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("translations", $result[ "error" ]);
		});

		$this->specify("create with empty translations", function () {
			$this->model->translations = [];

			$result = Model::createWithTranslations($this->model->translations);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("tag_id", $result);

			$this->tester->canSeeRecord(Model::className(), [ "id" => $result[ "tag_id" ] ]);
			$this->tester->cantSeeRecord(TagLangEx::className(), [ "tag_id" => $result[ "tag_id" ] ]);
		});
		$this->specify("create with translations", function () {
			$this->model->translations = [
				[ "lang_id" => LangEx::FR, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
				[ "lang_id" => LangEx::EN, "name" => $this->faker->text(), "slug" => $this->faker->text() ],
			];

			$result = Model::createWithTranslations($this->model->translations);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("tag_id", $result);

			$this->tester->canSeeRecord(Model::className(), [ "id" => $result[ "tag_id" ] ]);
			$this->tester->canSeeInDatabase(TagLangEx::tableName(), [ "tag_id" => $result[ "tag_id" ] ]);
		});
	}

	public function testDeleteWithTranslations ()
	{
		$this->specify("not delete invalid tag ID", function () {
			$result = Model::deleteWithTranslations(1000);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Model::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not delete tag with associated to published posts", function () {
			$tagId  = $this->tester->grabFixture("tag", "1")->id;
			$result = Model::deleteWithTranslations($tagId);
			
			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Model::ERR_DELETE_POSTS, $result[ "error" ]);
		});
		$this->specify("delete tag without translations", function () {
			$tagId  = $this->tester->grabFixture("tag", "4")->id;
			$result = Model::deleteWithTranslations($tagId);
			
			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->cantSeeInDatabase(Model::tableName(), [ "id" => $tagId ]);
		});
		$this->specify("delete tag with translations", function () {
			$tagId  = $this->tester->grabFixture("tag", "5")->id;

			$this->tester->canSeeNumRecords(1, Model::tableName(), [ "id" => $tagId ]);
			$this->tester->canSeeNumRecords(2, TagLangEx::tableName(), [ "tag_id" => $tagId ]);

			$result = Model::deleteWithTranslations($tagId);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->canSeeNumRecords(0, Model::tableName(), [ "id" => $tagId ]);
			$this->tester->canSeeNumRecords(0, TagLangEx::tableName(), [ "tag_id" => $tagId ]);
		});
	}

	public function testUpdateWithTranslations ()
	{
		$this->specify("not update with invalid tag ID", function () {
			$result = Model::updateWithTranslations(1000, []);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Model::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not update with invalid translations", function () {
			$tagId  = $this->tester->grabFixture("tag", 2)->id;
			$models = [
				[ "lang_id" => LangEx::EN, "slug" => $this->faker->slug() ],
			];

			$result = Model::updateWithTranslations($tagId, $models);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("translations", $result[ "error" ]);
		});

		$this->specify("create 1 translation", function () {
			$this->model = $this->tester->grabFixture("tag", 2);
			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "name" => $this->faker->name(), "slug" => $this->faker->slug() ],
			];

			$this->tester->canSeeNumRecords(1, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);

			$result = Model::updateWithTranslations($this->model->id, $this->model->translations);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("tag", $result);

			$this->tester->canSeeNumRecords(2, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);
		});
		$this->specify("update 1 translation", function () {
			$this->model = $this->tester->grabFixture("tag", 3);
			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "name" => $this->faker->name(), "slug" => $this->faker->slug() ],
			];

			$this->tester->canSeeNumRecords(1, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);

			$result = Model::updateWithTranslations($this->model->id, $this->model->translations);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("tag", $result);

			$this->tester->canSeeNumRecords(1, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);
		});
		$this->specify("create 1 translation and update 1 translation", function () {
			$this->model = $this->tester->grabFixture("tag", 3);
			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "name" => $this->faker->name(), "slug" => $this->faker->slug() ],
				[ "lang_id" => LangEx::FR, "name" => $this->faker->name(), "slug" => $this->faker->slug() ],
			];

			$this->tester->canSeeNumRecords(1, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);

			$result = Model::updateWithTranslations($this->model->id, $this->model->translations);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("tag", $result);

			$this->tester->canSeeNumRecords(2, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);
		});
		$this->specify("update 2 translations", function () {
			$this->model = $this->tester->grabFixture("tag", 1);
			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "name" => $this->faker->name(), "slug" => $this->faker->slug() ],
				[ "lang_id" => LangEx::FR, "name" => $this->faker->name(), "slug" => $this->faker->slug() ],
			];

			$this->tester->canSeeNumRecords(2, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);

			$result = Model::updateWithTranslations($this->model->id, $this->model->translations);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("tag", $result);

			$this->tester->canSeeNumRecords(2, TagLangEx::tableName(), [ "tag_id" => $this->model->id ]);
		});
	}
}