<?php

namespace app\tests\unit\post;

use app\models\post\Post;
use app\models\post\PostStatus;
use app\tests\_support\_fixtures\CategoryFixture;
use app\tests\_support\_fixtures\PostFixture;
use app\tests\_support\_fixtures\PostLangFixture;
use Faker\Factory as Faker;

/**
 * Class PostTest
 *
 * @package app\tests\unit\post
 */
class PostTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/** @var Post */
	protected $model;

	protected function _before ()
	{
		$this->faker = Faker::create();

		$this->tester->haveFixtures([
										"category" => CategoryFixture::className(),
										"postLang" => PostLangFixture::className(),
										"post"     => PostFixture::className(),
									]);
	}

	protected function _after () { }

	public function testValidation ()
	{
		$this->model = new Post();

		$this->specify("category id is required", function () {
			$this->_fieldsError($this->model, "category_id", Post::ERR_FIELD_REQUIRED);
		});
		$this->specify("category id is expected to be an integer", function () {
			$this->model->category_id = "invalid";

			$this->_fieldsError($this->model, "category_id", Post::ERR_FIELD_TYPE);
		});
		$this->specify("category id is expected to exists in category table", function () {
			$this->model->category_id = 1000;

			$this->_fieldsError($this->model, "category_id", Post::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("post status id is expected to be an integer", function () {
			$this->model->post_status_id = "published";

			$this->_fieldsError($this->model, "post_status_id", Post::ERR_FIELD_TYPE);
		});
		$this->specify("post status id is expected to exists in post status table", function () {
			$this->model->post_status_id = 1000;

			$this->_fieldsError($this->model, "post_status_id", Post::ERR_FIELD_NOT_FOUND);
		});

		$this->specify("valid model", function () {
			$this->model->category_id    = $this->tester->grabFixture("category", "active")->id;
			$this->model->post_status_id = PostStatus::UNPUBLISHED;

			$this->tester->assertTrue($this->model->validate());
		});
	}

	public function testCreate ()
	{
		$this->model = new Post();

		$this->specify("not create category not found", function () {
			$this->model->category_id = 1000;

			$result = Post::createPost($this->model);

			$this->tester->assertEquals(Post::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Post::ERR_CATEGORY_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not create post status not found", function () {
			$this->model->category_id 	 = $this->tester->grabFixture("category", "inactive")->id;
			$this->model->post_status_id = 1000;

			$result = Post::createPost($this->model);

			$this->tester->assertEquals(Post::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Post::ERR_STATUS_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("create valid model", function () {
			$this->model->category_id    = $this->tester->grabFixture("category", "inactive")->id;
			$this->model->post_status_id = PostStatus::UNPUBLISHED;

			$result = Post::createPost($this->model);

			$this->tester->assertEquals(Post::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("post_id", $result);

			$this->tester->canSeeInDatabase(Post::tableName(), [ "id" => $result[ "post_id" ] ]);
		});
		$this->specify("create valid model without post status", function () {
			$this->model->category_id    = $this->tester->grabFixture("category", "active")->id;

			$result = Post::createPost($this->model);

			$this->tester->assertEquals(Post::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("post_id", $result);

			$this->tester->canSeeInDatabase(Post::tableName(), [ "id" => $result[ "post_id" ], "post_status_id" => PostStatus::DRAFT ]);
		});
	}
	public function testDelete () {
		$this->specify("not delete a invalid post id", function () {
			$result = Post::deletePost(1000);

			$this->tester->assertEquals(Post::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Post::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not delete a published post", function () {
			$model  = $this->tester->grabFixture("post", "published");
			$result = Post::deletePost($model->id);

			$this->tester->assertEquals(Post::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Post::ERR_POST_PUBLISHED, $result[ "error" ]);

			$this->tester->canSeeInDatabase(Post::tableName(), [ "id" => $model->id ]);
		});
		$this->specify("delete a post", function () {
			$model  = $this->tester->grabFixture("post", "unpublished");
			$result = Post::deletePost($model->id);

			$this->tester->assertEquals(Post::SUCCESS, $result[ "status" ]);
			$this->tester->cantSeeInDatabase(Post::tableName(), [ "id" => $model->id ]);
		});
	}

	public function testUpdate () {}

	/**
	 * @param Post   $model
	 * @param string $field
	 * @param string $error
	 */
	protected function _fieldsError ( $model, $field, $error )
	{
		$this->tester->assertFalse($model->validate([ $field ]));
		$this->tester->assertContains($error, $model->getErrors($field));
	}
}