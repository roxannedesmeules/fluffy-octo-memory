<?php

namespace tests\unit\post;

use app\models\post\Post;
use app\models\post\PostStatus;
use app\tests\fixtures;
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
	}

	protected function _after () { }

	public function _fixtures ()
	{
		return [
			"category" => fixtures\CategoryFixture::class,
			"postLang" => fixtures\PostLangFixture::class,
			"post"     => fixtures\PostFixture::class,
		];
	}

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
			$this->model->category_id    = $this->tester->grabFixture("category", "category0")->id;
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

			$this->_error(Post::ERR_CATEGORY_NOT_FOUND, $result);
		});
		$this->specify("not create post status not found", function () {
			$this->model->category_id 	 = $this->tester->grabFixture("category", "category0")->id;
			$this->model->post_status_id = 1000;

			$result = Post::createPost($this->model);

			$this->_error(Post::ERR_STATUS_NOT_FOUND, $result);
		});
		$this->specify("create valid model", function () {
			$this->model->category_id    = $this->tester->grabFixture("category", "category0")->id;
			$this->model->post_status_id = PostStatus::UNPUBLISHED;

			$result = Post::createPost($this->model);

			$this->tester->assertEquals(Post::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("post_id", $result);

			$this->tester->canSeeRecord(Post::class, [ "id" => $result[ "post_id" ] ]);
		});
		$this->specify("create valid model without post status", function () {
			$this->model->category_id    = $this->tester->grabFixture("category", "category1")->id;

			$result = Post::createPost($this->model);

			$this->tester->assertEquals(Post::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("post_id", $result);

			$this->tester->canSeeRecord(Post::class, [ "id" => $result[ "post_id" ], "post_status_id" => PostStatus::DRAFT ]);
		});
	}

	public function testDelete ()
	{
		$this->specify("not delete a invalid post id", function () {
			$result = Post::deletePost(1000);

			$this->_error(Post::ERR_NOT_FOUND, $result);
		});
		$this->specify("not delete a published post", function () {
			$model  = $this->tester->grabFixture("post", "post3");
			$result = Post::deletePost($model->id);

			$this->_error(Post::ERR_POST_PUBLISHED, $result);

			$this->tester->canSeeRecord(Post::class, [ "id" => $model->id ]);
		});
		$this->specify("not delete a post with comments", function () {
			$model  = $this->tester->grabFixture("post", "post0");
			$result = Post::deletePost($model->id);

			$this->_error(Post::ERR_POST_DELETE_COMMENTS, $result);

			$this->tester->canSeeRecord(Post::class, [ "id" => $model->id ]);
		});
		$this->specify("delete a post", function () {
			$model  = $this->tester->grabFixture("post", "post4");
			$result = Post::deletePost($model->id);

			$this->tester->assertEquals(Post::SUCCESS, $result[ "status" ]);
			$this->tester->cantSeeRecord(Post::class, [ "id" => $model->id ]);
		});
	}

	public function testUpdate ()
	{
		$this->model = $this->tester->grabFixture("post", "post6");

		$this->specify("not update invalid post id", function () {
			$result = Post::updatePost(1000, $this->model);

			$this->_error(Post::ERR_NOT_FOUND, $result);
		});
		$this->specify("not update invalid category id", function () {
			$this->model->category_id = 1000;

			$result = Post::updatePost($this->model->id, $this->model);

			$this->_error(Post::ERR_CATEGORY_NOT_FOUND, $result);
		});
		$this->specify("not update invalid post status id", function () {
			$this->model->post_status_id = 1000;

			$result = Post::updatePost($this->model->id, $this->model);

			$this->_error(Post::ERR_STATUS_NOT_FOUND, $result);
		});
		$this->specify("update valid model", function () {
			$this->model->category_id = $this->tester->grabFixture("category", "category2")->id;

			$result = Post::updatePost($this->model->id, $this->model);

			$this->tester->assertEquals(Post::SUCCESS, $result[ "status" ]);
			$this->tester->canSeeRecord(Post::class, [ "id" => $this->model->id, "category_id" => $this->model->category_id ]);
		});
	}

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

	/**
	 * @param string $error
	 * @param array  $result
	 */
	public function _error ( $error, $result )
	{
		$this->tester->assertEquals(Post::ERROR, $result[ "status" ]);
		$this->tester->assertEquals($error, $result[ "error" ]);
	}
}