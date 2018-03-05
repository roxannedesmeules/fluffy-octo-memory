<?php
namespace app\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class AssoPostTagFixture
 *
 * @package app\tests\_support\_fixtures
 */
class AssoTagPostFixture extends ActiveFixture
{
	public $modelClass = 'app\models\tag\AssoTagPost';

	public $dataFile   = '/app/tests/_data/tag/asso_tag_post.php';

	public $depends = [
		'app\tests\_support\_fixtures\PostFixture',
		'app\tests\_support\_fixtures\TagFixture',
	];
}
