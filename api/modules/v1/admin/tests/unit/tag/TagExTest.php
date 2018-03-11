<?php

namespace app\modules\v1\admin\tests\unit\tag;

use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\tag\TagEx as Model;
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

	public function testCreateWithTranslations () {}
	public function testDeleteWithTranslations () {}
	public function testUpdateWithTranslations () {}
}