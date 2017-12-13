<?php
namespace app\tests\unit\category;

use app\models\category\Category;
use app\tests\_support\_fixtures\CategoryFixture;

/**
 * Class CategoryTest
 *
 * @package app\tests\unit\category
 *
 * @group   category
 */
class CategoryTest extends \Codeception\Test\Unit
{
	use \Codeception\Specify;

	/** @var \UnitTester */
	protected $tester;

	/** @var Category  @specify */
	protected $model;

	/** @inheritdoc */
	protected function _before () { }

	/** @inheritdoc */
	protected function _after () { }

	public function _fixtures ()
	{
		return [
			"category" => [
				"class" => CategoryFixture::className(),
			],
		];
	}

	public function testValidation ()
	{
		$this->model = new Category();

		$this->specify("is_active is expected to be an integer", function () {
			$this->model->is_active = false;

			$this->tester->assertFalse($this->model->validate([ "is_active" ]));
			$this->tester->assertContains(Category::ERR_FIELD_TYPE, $this->model->getErrors("is_active"));
		});
	}

	public function testCreate ()
	{
		$this->model = new Category();

		$this->specify("not create an invalid category", function () {
			$this->model->is_active = false;

			$result = Category::createCategory($this->model);

			$this->tester->assertEquals(Category::ERROR, $result[ "status" ]);
			$this->tester->assertTrue(is_array($result[ "error" ]));
		});

		$this->specify("create category", function () {
			$this->model->is_active = 0;

			$result = Category::createCategory($this->model);

			$this->tester->assertEquals(Category::SUCCESS, $result[ "status" ]);
			$this->tester->assertArrayHasKey("category_id", $result);
			
			$this->tester->canSeeRecord(Category::className(), [ "id" => $result[ "category_id" ] ]);
		});
	}

	public function testUpdate ()
	{
		$this->model = $this->tester->grabFixture('app\tests\_support\_fixtures\CategoryFixture', "inactive");

		$this->specify("not update a non-existing category", function () {
			$result = Category::updateCategory(1000, $this->model);

			$this->tester->assertEquals(Category::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Category::ERR_NOT_FOUND, $result[ "error" ]);
		});

		$this->specify("not update an invalid category", function () {
			$this->model->is_active = false;

			$result = Category::updateCategory($this->model->id, $this->model);

			$this->tester->assertEquals(Category::ERROR, $result[ "status" ]);
			$this->tester->assertTrue(is_array($result[ "error" ]));
		});

		$this->specify("update category", function () {
			$this->model->is_active = 1;

			$result = Category::updateCategory($this->model->id, $this->model);

			$this->tester->assertEquals(Category::SUCCESS, $result[ "status" ]);

			$this->tester->canSeeRecord(Category::className(), [ "id" => $this->model->id, "is_active" => 1 ]);
		});
	}

	public function testDelete ()
	{
		$this->specify("not delete a non-existing category", function () {
			$result = Category::deleteCategory(1000);

			$this->tester->assertEquals(Category::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Category::ERR_NOT_FOUND, $result[ "error" ]);
		});

		$this->specify("not delete active category", function () {
			$model  = $this->tester->grabFixture('app\tests\_support\_fixtures\CategoryFixture', "active");
			$result = Category::deleteCategory($model->id);

			$this->tester->assertEquals(Category::ERROR, $result[ "status" ]);
			$this->tester->assertEquals(Category::ERR_DELETE_ACTIVE, $result[ "error" ]);
		});

		$this->specify("delete an existing category", function () {
			$model  = $this->tester->grabFixture('app\tests\_support\_fixtures\CategoryFixture', "nolang");
			$result = Category::deleteCategory($model->id);

			$this->tester->assertEquals(Category::SUCCESS, $result[ "status" ]);
		});
	}
}