<?php
namespace app\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class CategoryFixture
 *
 * @package app\tests\_support\_fixtures
 */
class CategoryFixture extends ActiveFixture
{
	public $modelClass = 'app\models\category\Category';

	public $dataFile   = 'tests/_data/category.php';
}
