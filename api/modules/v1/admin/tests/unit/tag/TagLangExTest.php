<?php

namespace app\modules\v1\admin\tests\unit\tag;

use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\tag\TagLangEx as Model;
use app\modules\v1\admin\tests\_support\_fixtures\TagExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\TagLangExFixture;
use Faker\Factory as Faker;

/**
 * Class TagLangExTest
 *
 * @package app\modules\v1\admin\tests\unit\tag
 *
 * @group   tag
 */
class TagLangExTest extends \Codeception\Test\Unit
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
			"tagLang" => TagLangExFixture::className(),
			"tag"     => TagExFixture::className(),
		]);
	}

	protected function _after () { }

	// tests
	public function testValidation ()
	{
		$this->model = new Model();

		$this->specify("lang_id is required", function () {
			$this->_fieldError($this->model, "lang_id", Model::ERR_FIELD_REQUIRED);
		});
		$this->specify("lang_id is expected to exists in lang table", function () {
			$this->model->lang_id = 1000;

			$this->_fieldError($this->model, "lang_id", Model::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("name is required", function () {
			$this->_fieldError($this->model, "name", Model::ERR_FIELD_REQUIRED);
		});
		$this->specify("name is expected to be less than 255 characters", function () {
			$this->model->name = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->_fieldError($this->model, "name", Model::ERR_FIELD_TOO_LONG);
		});
		$this->specify("name is expected to be unique for a language", function () {
			$this->model->lang_id = LangEx::FR;
			$this->model->name    = $this->tester->grabFixture("tagLang", "1-fr")->name;

			$this->_fieldError($this->model, "name", Model::ERR_FIELD_NOT_UNIQUE);

			$this->model->lang_id = LangEx::EN;

			$this->tester->assertTrue($this->model->validate([ "name" ]), "name can be a duplicate if in another language");
		});

		$this->specify("slug is required", function () {
			$this->_fieldError($this->model, "slug", Model::ERR_FIELD_REQUIRED);
		});
		$this->specify("slug is expected to be less than 255 characters", function () {
			$this->model->slug = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->_fieldError($this->model, "slug", Model::ERR_FIELD_TOO_LONG);
		});
		$this->specify("slug is expected to be unique for a language", function () {
			$this->model->lang_id = LangEx::FR;
			$this->model->slug    = $this->tester->grabFixture("tagLang", "1-fr")->slug;

			$this->_fieldError($this->model, "slug", Model::ERR_FIELD_NOT_UNIQUE);

			$this->model->lang_id = LangEx::EN;

			$this->tester->assertTrue($this->model->validate([ "slug" ]), "slug can be a duplicate if in another language");
		});

		$this->specify("valid translation", function () {
			$this->model->lang_id = LangEx::EN;
			$this->model->name    = $this->faker->name();
			$this->model->slug    = $this->faker->slug();

			$this->tester->assertTrue($this->model->validate());
		});
	}

	public function testManageTranslation ()
	{
		$this->specify("not create/update with invalid tag ID", function () {
			$result = Model::manageTranslations(1000, []);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Model::ERR_TAG_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create/update if one model has invalid language", function () {
			$tagId  = $this->tester->grabFixture("tag", "1")->id;
			$models = [
				[ "lang_id" => 1000, "name" => $this->faker->text(), "slug", $this->faker->slug() ],
			];

			$result = Model::manageTranslations($tagId, $models);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Model::ERR_LANG_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create/update if one model is invalid", function () {
			$tagId  = $this->tester->grabFixture("tag", "2")->id;
			$models = [
				[ "lang_id" => LangEx::EN, "slug", $this->faker->slug() ],
			];

			$result = Model::manageTranslations($tagId, $models);

			$this->tester->assertEquals(Model::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("name", $result[ "error" ]);
		});

		$this->specify("create 1 model, then update it if same language", function () {
			$tagId  = $this->tester->grabFixture("tag", "4")->id;
			$models = [
				[ "lang_id" => LangEx::FR, "name" => $this->faker->name(), "slug" => $this->faker->slug(), ],
				[ "lang_id" => LangEx::FR, "name" => $this->faker->name(), "slug" => $this->faker->slug(), ],
			];

			$result = Model::manageTranslations($tagId, $models);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->seeNumRecords(1, Model::tableName(), [ "tag_id" => $tagId ]);
		});
		$this->specify("create both model", function () {
			$tagId  = $this->tester->grabFixture("tag", "4")->id;
			$models = [
				[ "lang_id" => LangEx::FR, "name" => $this->faker->name(), "slug" => $this->faker->slug(), ],
				[ "lang_id" => LangEx::EN, "name" => $this->faker->name(), "slug" => $this->faker->slug(), ],
			];

			$result = Model::manageTranslations($tagId, $models);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->seeNumRecords(2, Model::tableName(), [ "tag_id" => $tagId ]);
		});
		$this->specify("create 1 model and update 1 model", function () {
			$tagId  = $this->tester->grabFixture("tag", "2")->id;
			$models = [
				[ "lang_id" => LangEx::FR, "name" => $this->faker->name(), "slug" => $this->faker->slug(), ],
				[ "lang_id" => LangEx::EN, "name" => $this->faker->name(), "slug" => $this->faker->slug(), ],
			];

			$this->tester->seeNumRecords(1, Model::tableName(), [ "tag_id" => $tagId ]);

			$result = Model::manageTranslations($tagId, $models);

			$this->tester->assertEquals(Model::SUCCESS, $result[ "status" ]);
			$this->tester->seeNumRecords(2, Model::tableName(), [ "tag_id" => $tagId ]);
		});
	}

	/**
	 * @param \app\modules\v1\admin\models\tag\TagLangEx $model
	 * @param string                                     $field
	 * @param string                                     $expected
	 */
	private function _fieldError ( Model $model, string $field, string $expected )
	{
		$this->tester->assertFalse($model->validate([ $field ]));
		$this->tester->assertContains($expected, $model->getErrors($field));
	}
}