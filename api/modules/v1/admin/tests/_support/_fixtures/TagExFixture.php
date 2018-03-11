<?php
namespace app\modules\v1\admin\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class TagExFixture
 *
 * @package app\modules\v1\admin\tests\_support\_fixtures
 */
class TagExFixture extends ActiveFixture
{
	public $modelClass = 'app\modules\v1\admin\models\tag\TagEx';

	public $dataFile   = '/app/tests/_data/tag/tag.php';
}
