<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class CategoryFixture
 *
 * @package tests\fixtures
 */
class CategoryFixture extends ActiveFixture
{
	public $modelClass = 'app\models\category\Category';

	public $dataFile   = "@tests/fixtures/data/category.php";
}
