<?php
namespace app\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class PostFixture
 *
 * @package app\tests\_support\_fixtures
 */
class PostFixture extends ActiveFixture
{
	public $modelClass = 'app\models\post\Post';

	public $dataFile   = '/app/tests/_data/post/post.php';

	public $depends    = [
		'app\tests\_support\_fixtures\CategoryFixture',
	];
}
