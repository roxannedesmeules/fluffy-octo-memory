<?php

namespace app\tests\unit\tag;

use app\models\tag\Tag;
use app\tests\_support\_fixtures\AssoTagPostFixture;
use app\tests\_support\_fixtures\TagFixture;
use app\tests\_support\_fixtures\TagLangFixture;
use \Faker\Factory as Faker;

/**
 * Class TagTest
 *
 * @package app\tests\unit\tag
 *
 * @group   tag
 */
class TagTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var \Faker\Generator */
	protected $faker;

	/**
	 * @var Tag
	 * @specify
	 */
	protected $model;

	/** @inheritdoc */
	protected function _before ()
	{
		$this->faker = Faker::create();

		$this->tester->haveFixtures([
			"tag" => TagFixture::className(),
			"tagLang" => TagLangFixture::className(),
			"asso"    => AssoTagPostFixture::className(),
		]);
	}

	/** @inheritdoc */
	protected function _after () { }

	public function testCreate () 
	{
		$this->specify("create a valid tag", function () {
			$result = Tag::createTag();

			$this->tester->assertEquals(Tag::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("tag_id", $result);

			$this->tester->canSeeRecord(Tag::className(), [ "id" => $result[ "tag_id" ] ]);
		});
	}
	
	public function testUpdate () 
	{
		$this->specify("not update a non-existing tag", function () {
			$result = Tag::updateTag(1000);

			$this->tester->assertEquals(tag::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(tag::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("update a valid tag", function () {
			$tagId  = $this->tester->grabFixture("tag", "1")->id;
			$result = Tag::updateTag($tagId);

			$this->tester->assertEquals(Tag::SUCCESS, $result[ "status" ]);
		});
	}
	
	public function testDelete () 
	{
		$this->specify("not delete an invalid tag id", function () {
			$result = Tag::deleteTag(1000);

			$this->tester->assertEquals(Tag::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Tag::ERR_NOT_FOUND, $result[ "error" ]);
		});
		$this->specify("not delete tag with translations", function () {
			$tagId  = $this->tester->grabFixture("tag", "3")->id;
			$result = Tag::deleteTag($tagId);

			$this->tester->assertEquals(Tag::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Tag::ERR_HAS_TRANSLATIONS, $result[ "error" ]);
		});
		$this->specify("not delete tag associated to published post", function () {
			$tagId  = $this->tester->grabFixture("tag", "1")->id;
			$result = Tag::deleteTag($tagId);

			$this->tester->assertEquals(Tag::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Tag::ERR_DELETE_POSTS, $result[ "error" ]);
		});
		$this->specify("delete a valid tag id", function () {
			$tagId  = $this->tester->grabFixture("tag", "4")->id;
			$result = Tag::updateTag($tagId);

			$this->tester->assertEquals(Tag::SUCCESS, $result[ "status" ]);
		});
	}
}