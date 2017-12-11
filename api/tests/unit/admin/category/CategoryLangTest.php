<?php
namespace app\tests\unit\admin\category;

use app\models\category\CategoryLang as Model;
use app\tests\_support\_fixtures\CategoryLangFixture;
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
		$this->specify("name is expected to be unique", function () {
			$this->model->name = "not-unique";

			$this->tester->assertFalse($this->model->validate([ "name" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_UNIQUE, $this->model->getErrors("name"));
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
		$this->specify("slug is expected to be unique", function () {
			$this->model->slug = "unique";

			$this->tester->assertFalse($this->model->validate([ "slug" ]));
			$this->tester->assertContains(Model::ERR_FIELD_NOT_UNIQUE, $this->model->getErrors("slug"));
		});
	}

	public function testCreate () {}

	public function testUpdate () {}

	public function testDelete () {}
}