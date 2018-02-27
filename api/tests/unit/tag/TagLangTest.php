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
			$this->_fieldError($this->model, "lang_id", TagLang::ERR_FIELD_REQUIRED);
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

			$this->tester->assertTrue($this->model->validate([ "post_id", "lang_id" ]));
		});

		$this->specify("name is required", function () {
			$this->_fieldError($this->model, "name", TagLang::ERR_FIELD_REQUIRED);
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
	}

	public function testCreate () {}
	public function testDeleteAll () {}
	public function testUpdate () {}

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