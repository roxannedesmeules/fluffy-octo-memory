<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class PostLangFixture
 *
 * @package app\tests\unit\fixtures
 */
class PostLangFixture extends ActiveFixture
{
	public $modelClass = 'app\models\post\PostLang';

	public $dataFile   = "@tests/fixtures/data/post_lang.php";

	public $depends = [
		PostFixture::class,
		FileFixture::class,
	];
}
