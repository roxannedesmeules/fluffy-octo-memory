<?php
namespace app\modules\v1\admin\tests\unit\post;

use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\post\PostLangEx;
use app\modules\v1\admin\tests\_support\_fixtures\PostExFixture;
use app\modules\v1\admin\tests\_support\_fixtures\PostLangExFixture;
use Faker\Factory as Faker;

/**
 * Class PostLangExTest
 *
 * @package app\modules\v1\admin\tests\unit\post
 *
 * @group   post
 */
class PostLangExTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/** @var PostLangEx */
	protected $model;

	/** @inheritdoc */
	protected function _before ()
	{
		$this->faker = Faker::create();

		$this->tester->haveFixtures([
			"postLangEx" => PostLangExFixture::className(),
			"postEx"     => PostExFixture::className(),
		]);
	}

	/** @inheritdoc */
	protected function _after () { }

	public function testValidation ()
	{
		$this->model = new PostLangEx();

		$this->specify("lang id is required", function () {
			$this->_fieldsError($this->model, "lang_id", PostLangEx::ERR_FIELD_REQUIRED);
		});
		$this->specify("lang id is expected to exists in lang table", function () {
			$this->model->lang_id = 1000;

			$this->_fieldsError($this->model, "lang_id", PostLangEx::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("title is required", function () {
			$this->_fieldsError($this->model, "title", PostLangEx::ERR_FIELD_REQUIRED);
		});
		$this->specify("title is expected to be less than 255 characters", function () {
			$this->model->title = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->_fieldsError($this->model, "title", PostLangEx::ERR_FIELD_TOO_LONG);
		});

		$this->specify("slug is required", function () {
			$this->_fieldsError($this->model, "slug", PostLangEx::ERR_FIELD_REQUIRED);
		});
		$this->specify("slug is expected to be less than 255 characters", function () {
			$this->model->slug = \Yii::$app->getSecurity()->generateRandomString(256);

			$this->_fieldsError($this->model, "slug", PostLangEx::ERR_FIELD_TOO_LONG);
		});
		$this->specify("slug is expected to be unique by language", function () {
			$this->model->lang_id = LangEx::FR;
			$this->model->slug    = $this->tester->grabFixture("postLangEx", "published-fr")->slug;

			$this->_fieldsError($this->model, "slug", PostLangEx::ERR_FIELD_NOT_UNIQUE);
		});

		$this->specify("content is expected to be a string", function () {
			$this->model->content = $this->faker->paragraphs();

			$this->_fieldsError($this->model, "content", PostLangEx::ERR_FIELD_TYPE);
		});

		$this->specify("valid model", function () {
			$this->model->lang_id = LangEx::FR;
			$this->model->title   = $this->faker->text();
			$this->model->slug    = $this->faker->slug();

			$this->tester->assertTrue($this->model->validate());
		});
	}

	public function testManageTranslations ()
	{
		$this->specify("not create/update with invalid post", function () {
			$result = PostLangEx::manageTranslations(1000, []);

			$this->tester->assertEquals(PostLangEx::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(PostLangEx::ERR_POST_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create/update if one model has invalid language", function () {
			$postId = $this->tester->grabFixture("postEx", "archived")->id;
			$models = [
				[ "lang_id" => 1000, "title" => $this->faker->text(), "slug" => $this->faker->slug() ],
			];

			$result = PostLangEx::manageTranslations($postId, $models);

			$this->tester->assertEquals(PostLangEx::ERROR, $result[ 0 ][ "status" ]);
			$this->tester->assertEquals(PostLangEx::ERR_LANG_NOT_FOUND, $result[ 0 ][ "error" ]);
		});
		$this->specify("not create/update if one model is invalid", function () {
			$postId = $this->tester->grabFixture("postEx", "archived")->id;
			$models = [
				$this->tester->grabFixture("postLangEx", "archived-en"),
				[ "lang_id" => LangEx::FR, "slug" => $this->faker->slug() ],
			];

			$result = PostLangEx::manageTranslations($postId, $models);

			$this->tester->assertEquals(PostLangEx::ERROR, $result[ 1 ][ "status" ]);
			$this->tester->assertArrayHasKey("title", $result[ 1 ][ "error" ]);
		});

		$this->specify("create one model, then update it if same language passed several times", function () {
			$postId = $this->tester->grabFixture("postEx", "unpublished")->id;
			$models = [
				[ "lang_id" => LangEx::FR, "title" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => LangEx::FR, "title" => $this->faker->text(), "slug" => $this->faker->slug(), "content" => $this->faker->paragraphs(3, true) ],
			];

			$result = PostLangEx::manageTranslations($postId, $models);

			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 0 ][ "status" ]);
			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 1 ][ "status" ]);

			$this->tester->canSeeNumRecords(1, PostLangEx::tableName(), [ "post_id" => $postId ]);
		});
		$this->specify("create both model in list", function () {
			$postId = $this->tester->grabFixture("postEx", "unpublished")->id;
			$models = [
				[ "lang_id" => LangEx::FR, "title" => $this->faker->text(), "slug" => $this->faker->slug() ],
				[ "lang_id" => LangEx::EN, "title" => $this->faker->text(), "slug" => $this->faker->slug(), "content" => $this->faker->paragraphs(3, true) ],
			];

			$result = PostLangEx::manageTranslations($postId, $models);

			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 0 ][ "status" ]);
			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 1 ][ "status" ]);

			$this->tester->canSeeNumRecords(2, PostLangEx::tableName(), [ "post_id" => $postId ]);
		});
		$this->specify("create one and update one model in list", function () {
			$postId = $this->tester->grabFixture("postEx", "archived")->id;
			$models = [
				[ "lang_id" => LangEx::EN, "title" => $this->faker->text(), ],
				[ "lang_id" => LangEx::FR, "title" => $this->faker->text(), "slug" => $this->faker->slug(), "content" => $this->faker->paragraphs(3, true) ],
			];

			$this->tester->canSeeNumRecords(1, PostLangEx::tableName(), [ "post_id" => $postId ]);

			$result = PostLangEx::manageTranslations($postId, $models);

			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 0 ][ "status" ]);
			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 1 ][ "status" ]);

			$this->tester->canSeeNumRecords(2, PostLangEx::tableName(), [ "post_id" => $postId ]);
		});
		$this->specify("update both model in list", function () {
			$postId = $this->tester->grabFixture("postEx", "draft")->id;
			$models = [
				[ "lang_id" => LangEx::EN, "title" => $this->faker->text(), ],
				[ "lang_id" => LangEx::FR, "slug" => $this->faker->slug(), "content" => $this->faker->paragraphs(3, true) ],
			];

			$this->tester->canSeeNumRecords(2, PostLangEx::tableName(), [ "post_id" => $postId ]);

			$result = PostLangEx::manageTranslations($postId, $models);

			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 0 ][ "status" ]);
			$this->tester->assertEquals(PostLangEx::SUCCESS, $result[ 1 ][ "status" ]);

			$this->tester->canSeeNumRecords(2, PostLangEx::tableName(), [ "post_id" => $postId ]);
		});
	}

	/**
	 * @param PostLangEx $model
	 * @param string     $field
	 * @param string     $error
	 */
	protected function _fieldsError ( $model, $field, $error )
	{
		$this->tester->assertFalse($model->validate([ $field ]));
		$this->tester->assertContains($error, $model->getErrors($field));
	}
}