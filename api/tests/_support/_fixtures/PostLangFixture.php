<?php
namespace app\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class PostLangFixture
 *
 * @package app\tests\_support\_fixtures
 */
class PostLangFixture extends ActiveFixture
{
	public $modelClass = 'app\models\post\PostLang';

	public $dataFile   = '/app/tests/_data/post_lang.php';

	public $depends = [
		'app\tests\_support\_fixtures\PostFixture',
	];
}
