<?php
namespace app\modules\v1\admin\tests\unit\post;

use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\post\PostEx;
use app\modules\v1\admin\models\post\PostStatusEx;
use app\modules\v1\admin\tests\_support\_fixtures\CategoryExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\PostExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\PostLangExFixture;
use Faker\Factory as Faker;

/**
 * Class PostExTest
 *
 * @package app\modules\v1\admin\tests\unit\post
 *
 * @group   post
 */
class PostExTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/**
	 * @var PostEx
	 * @specify
	 */
	protected $model;

	/** @inheritdoc */
	protected function _before ()
	{
		$this->faker = Faker::create();

		$this->tester->haveFixtures([
			"postLangEx" => PostLangExFixture::className(),
			"postEx"     => PostExFixture::className(),
			"categoryEx" => CategoryExFixture::className(),
		]);
	}

	/** @inheritdoc */
	protected function _after () { }

	public function testValidation ()
	{
		$this->model = new PostEx();

		$this->specify("category id is required", function () {
			$this->_fieldError($this->model, "category_id", PostEx::ERR_FIELD_REQUIRED);
		});
		$this->specify("category id is expected to be an integer", function () {
			$this->model->category_id = "wrong";

			$this->_fieldError($this->model, "category_id", PostEx::ERR_FIELD_TYPE);
		});
		$this->specify("category id is expected to exists in category table", function () {
			$this->model->category_id = 1000;

			$this->_fieldError($this->model, "category_id", PostEx::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("post status id is expected to be an integer", function () {
			$this->model->post_status_id = "wrong";

			$this->_fieldError($this->model, "post_status_id", PostEx::ERR_FIELD_TYPE);
		});
		$this->specify("post status id is expected to exists in post status table", function () {
			$this->model->post_status_id = 1000;

			$this->_fieldError($this->model, "post_status_id", PostEx::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("each translations is expected to have a different language", function () {
			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "title" => "test", "slug" => "test" ],
				[ "lang_id" => LangEx::EN, "title" => "test", "slug" => "test" ],
			];

			$this->_fieldError($this->model, "translations", PostEx::ERR_FIELD_UNIQUE_LANG);
		});
		$this->specify("each translations is expected to be a valid PostLangEx model", function () {
			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "title" => "test", "slug" => "test" ],
				[ "lang_id" => LangEx::FR, "slug" => "test" ],
			];

			$this->tester->assertFalse($this->model->validate([ "translations" ]));
			$this->tester->assertCount(1, $this->model->getErrors("translations"));
			$this->tester->assertArrayHasKey("title", $this->model->getErrors("translations")[ 0 ]);
		});

		$this->specify("valid model", function () {
			$this->model->category_id    = $this->tester->grabFixture("categoryEx", "inactive");
			$this->model->post_status_id = PostStatusEx::DRAFT;
			$this->model->translations   = [];

			$this->tester->assertTrue($this->model->validate(), "no translations is valid");

			$this->model->translations = [
				[ "lang_id" => LangEx::EN, "title" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$this->tester->assertTrue($this->model->validate(), "with translations is valid");
		});
	}

	/** @skip */
	public function testCreateWithTranslation () 
	{
		$this->specify("not create invalid model", function () { $this->tester->fail("not implemented"); });
		$this->specify("not create invalid translations", function () { $this->tester->fail("not implemented"); });
		$this->specify("create with translations", function () { $this->tester->fail("not implemented"); });
		$this->specify("create with empty translations", function () { $this->tester->fail("not implemented"); });
	}

	/** @skip */
	public function testDeleteWithTranslation () 
	{
		$this->specify("not delete invalid post id", function () { $this->tester->fail("not implemented"); });
		$this->specify("not delete published post", function () { $this->tester->fail("not implemented"); });
		$this->specify("delete with all translations", function () { $this->tester->fail("not implemented"); });
	}

	/** @skip */
	public function testUpdateWithTranslation ()
	{
		$this->specify("not update invalid post id", function () { $this->tester->fail("not implemented"); });
		$this->specify("not update invalid model", function () { $this->tester->fail("not implemented"); });
		$this->specify("not update invalid translations", function () { $this->tester->fail("not implemented"); });

		$this->specify("update category and create 2 translations", function () { $this->tester->fail("not implemented"); });
		$this->specify("update category and create 1 translation, update 1 translation", function () { $this->tester->fail("not implemented"); });
		$this->specify("update category and update 2 translations", function () { $this->tester->fail("not implemented"); });
	}

	/**
	 * @param PostEx $model
	 * @param string $field
	 * @param string $error
	 */
	private function _fieldError ( $model, $field, $error )
	{
		$this->tester->assertFalse($model->validate([ $field ]));
		$this->tester->assertContains($error, $model->getErrors($field));
	}

	private function _singleError () { }
}