<?php
namespace app\tests\unit\category;

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
	/** @var \UnitTester */
	protected $tester;

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

	}

	public function testCreate () { }

	public function testUpdate () { }

	public function testDelete () { }
}