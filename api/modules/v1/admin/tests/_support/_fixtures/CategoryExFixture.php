<?php
namespace app\modules\v1\admin\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class CategoryExFixture
 *
 * @package app\modules\v1\admin\tests\_support\_fixtures
 */
class CategoryExFixture extends ActiveFixture
{
	public $modelClass = 'app\modules\v1\admin\models\category\CategoryEx';

	public $dataFile   = '/app/tests/_data/category.php';
}
