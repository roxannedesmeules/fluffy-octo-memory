<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class PostFixture
 *
 * @package app\tests\fixtures
 */
class PostFixture extends ActiveFixture
{
	public $modelClass = 'app\models\post\Post';

	public $dataFile   = "@tests/fixtures/data/post.php";

	public $depends = [
		'app\tests\fixtures\CategoryFixture',
	];
}
