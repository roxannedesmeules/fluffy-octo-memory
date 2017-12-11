<?php
namespace app\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class CategoryLangFixture
 *
 * @package app\tests\_support\_fixtures
 */
class CategoryLangFixture extends ActiveFixture
{
	public $modelClass = 'app\models\category\CategoryLang';

	public $dataFile   = 'tests/_data/category_lang.php';

	public $depends    = [
		'app\tests\_support\_fixtures\CategoryFixture',
	];
}
