<?php
namespace app\modules\v1\admin\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class PostLangExFixture
 *
 * @package app\modules\v1\admin\tests\_support\_fixtures
 */
class PostLangExFixture extends ActiveFixture
{
	public $modelClass = 'app\modules\v1\admin\models\post\PostLangEx';
	public $dataFile   = '/app/tests/_data/post/lang.php';

	public $depends = [
		'app\modules\v1\admin\tests\_support\_fixtures\PostExFixture',
	];
}
