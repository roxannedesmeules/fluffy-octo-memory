<?php
namespace app\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class TagFixture
 *
 * @package app\tests\_support\_fixtures
 */
class TagFixture extends ActiveFixture
{
	public $modelClass = 'app\models\tag\Tag';

	public $dataFile   = '/app/tests/_data/tag/tag.php';
}
