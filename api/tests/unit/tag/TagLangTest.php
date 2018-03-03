<?php

namespace app\tests\unit\tag;

use app\models\app\Lang;
use app\models\tag\TagLang;
	use app\tests\_support\_fixtures\TagLangFixture;
use \Faker\Factory as Faker;

/**
 * Class TagLangTest
 *
 * @package app\tests\unit\tag
 *
 * @group   tag
 */
class TagLangTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/**
	 * @var TagLang
	 * @specify
	 */
	protected $model;

	/** @inheritdoc */
	protected function _before ()
	{
		$this->faker = Faker::create();

		$this->tester->haveFixtures([ "tagLang" => TagLangFixture::className() ]);
	}

	/** @inheritdoc */
	protected function _after () { }

	// tests
	public function testValidation ()
	{
		$this->model = new TagLang();

		$this->specify("tag_id is required", function () {
			$this->_fieldsError($this->model, "tag_id", TagLang::ERR_FIELD_REQUIRED);
		});
		$this->specify("tag_id is expected to exists in tag table", function () {
			$this->model->tag_id = 1000;

			$this->_fieldsError($this->model, "tag_id", TagLang::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("lang_id is required", function () {
			$this->_fieldsError($this->model, "lang_id", TagLang::ERR_FIELD_REQUIRED);
		});
		$this->specify("lang_id is expected to exists in lang table", function () {
			$this->model->lang_id = 1000;

			$this->_fieldsError($this->model, "lang_id", TagLang::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("tag_id and lang_id are expected to be a unique combo", function () {
			$this->model->tag_id  = 2;
			$this->model->lang_id = Lang::FR;

			$this->_fieldsError($this->model, "tag_id", TagLang::ERR_FIELD_NOT_UNIQUE);

			$this->model->tag_id  = 2;
			$this->model->lang_id = Lang::EN;

			$this->tester->assertTrue($this->model->validate([ "tag_id", "lang_id" ]));
		});

		$this->specify("name is required", function () {
			$this->_fieldsError($this->model, "name", TagLang::ERR_FIELD_REQUIRED);
		});
		$this->specify("name is excepted to be a string of less than 255 characters", function () {
			$this->model->name = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->_fieldsError($this->model, "name", TagLang::ERR_FIELD_TOO_LONG);
		});

		$this->specify("slug is expected to be a string of less than 255 characters", function () {
			$this->model->slug = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->_fieldsError($this->model, "slug", TagLang::ERR_FIELD_TOO_LONG);
		});
		$this->specify("slug is expected to be unique by language", function () {
			$temp = $this->tester->grabFixture("tagLang", "1-fr");

			$this->model->lang_id = $temp->lang_id;
			$this->model->slug    = $temp->slug;

			$this->_fieldsError($this->model, "slug", TagLang::ERR_FIELD_NOT_UNIQUE);
		});

		$this->specify("valid model", function () {
			$this->model->tag_id  = 3;
			$this->model->lang_id = Lang::FR;
			$this->model->name    = $this->faker->name();

			$this->assertTrue($this->model->validate());

			$this->model->slug = $this->faker->slug();

			$this->assertTrue($this->model->validate());
		});
	}

	/**
	 * Create a single translation :
	 * - invalid tag id
	 * - invalid lang id
	 * - invalid model
	 * - existing translation
	 */
	public function testCreate ()
	{
		$this->model = new TagLang();

		$this->specify("not create with invalid tag id", function () {
			$result = TagLang::createTranslation(1000, $this->model);

			$this->tester->assertEquals(TagLang::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(TagLang::ERR_TAG_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create with invalid lang id", function () {
			$this->model->lang_id = 1000;

			$result = TagLang::createTranslation(2, $this->model);

			$this->tester->assertEquals(TagLang::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(TagLang::ERR_LANG_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create an existing translation", function () {
			$this->model->lang_id = Lang::EN;

			$result = TagLang::createTranslation(1, $this->model);

			$this->tester->assertEquals(TagLang::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(TagLang::ERR_TRANSLATION_EXISTS, $result[ "error" ]);
		});
		$this->specify("not create with invalid model", function () {
			$this->model->lang_id = Lang::EN;
			$this->model->name    = \Yii::$app->getSecurity()->generateRandomString(256);

			$result = TagLang::createTranslation(2, $this->model);

			$this->tester->assertEquals(TagLang::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("name", $result[ "error" ]);
		});

		$this->specify("create valid translation", function () {
			$this->model->lang_id = Lang::EN;
			$this->model->name    = $this->faker->name();

			$result = TagLang::createTranslation(2, $this->model);

			$this->tester->assertEquals(TagLang::SUCCESS, $result[ "status" ]);
		});
	}

	/**
	 * Delete all translations :
	 * - not delete linked tags
	 * - delete translation
	 */
	public function testDeleteAll ()
	{
		$this->specify("not delete translations of a tag linked to a post", function () {});
		$this->specify("delete translations of a tag", function () {
			$model  = $this->tester->grabFixture("tagLang", "1-fr");

			$this->tester->seeNumRecords(2, TagLang::tableName(), [ "tag_id" => $model->tag_id ]);

			$result = TagLang::deleteTranslations($model->tag_id);

			$this->tester->seeNumRecords(0, TagLang::tableName(), [ "tag_id" => $model->tag_id ]);
			$this->tester->assertEquals(TagLang::SUCCESS, $result[ "status" ]);
		});
	}

	public function testUpdate ()
	{
		$this->model = $this->tester->grabFixture("tagLang", "2-fr");

		$this->specify("not update invalid translation id", function () {
			$result = TagLang::updateTranslation($this->model->tag_id, Lang::EN, $this->model);

			$this->tester->assertEquals(TagLang::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(TagLang::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not update invalid model", function () {
			$this->model->slug = $this->tester->grabFixture("tagLang", "1-fr")->slug;

			$result = TagLang::updateTranslation($this->model->tag_id, $this->model->lang_id, $this->model);

			$this->tester->assertEquals(TagLang::ERROR, $result[ "status" ]);
			$this->tester->assertArrayHasKey("slug", $result[ "error" ]);
		});
		$this->specify("update valid model", function () {
			$this->model->slug = $this->faker->slug();

			$result = TagLang::updateTranslation($this->model->tag_id, $this->model->lang_id, $this->model);

			$this->tester->assertEquals(TagLang::SUCCESS, $result[ "status" ]);
			$this->tester->canSeeInDatabase(TagLang::tableName(),
				[
					"tag_id"  => $this->model->tag_id,
					"lang_id" => $this->model->lang_id,
					"slug"    => $this->model->slug,
				]);
		});
	}

	/**
	 * @param TagLang $model
	 * @param string  $field
	 * @param string  $error
	 */
	protected function _fieldsError ( $model, $field, $error )
	{
		$this->tester->assertFalse($model->validate([ $field ]));
		$this->tester->assertContains($error, $model->getErrors($field));
	}
}