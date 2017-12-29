<?php
namespace app\modules\v1\admin\tests\_support\_fixtures;

use Faker\Factory;
use yii\test\ActiveFixture;

/**
 * Class CategoryLangFixture
 *
 * @package app\modules\v1\admin\tests\_support\_fixtures
 */
class CategoryLangExFixture extends ActiveFixture
{
	public $modelClass = 'app\modules\v1\admin\models\category\CategoryLangEx';

	public $dataFile   = '/app/tests/_data/category_lang.php';

	public $depends    = [
		'app\modules\v1\admin\tests\_support\_fixtures\CategoryExFixture',
	];
}
