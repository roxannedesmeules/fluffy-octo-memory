<?php
namespace app\modules\v1\admin\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class TagLangExFixture
 *
 * @package app\modules\v1\admin\tests\_support\_fixtures
 */
class TagLangExFixture extends ActiveFixture
{
	public $modelClass = 'app\modules\v1\admin\models\tag\TagLangEx';
	public $dataFile   = '/app/tests/_data/tag/lang.php';

	public $depends = [
		'app\modules\v1\admin\tests\_support\_fixtures\TagExFixture',
	];
}
