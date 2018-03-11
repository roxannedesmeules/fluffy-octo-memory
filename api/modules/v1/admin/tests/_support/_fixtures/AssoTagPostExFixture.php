<?php
namespace app\modules\v1\admin\tests\_support\_fixtures;

use yii\test\ActiveFixture;

/**
 * Class AssoPostTagExFixture
 *
 * @package app\tests\_support\_fixtures
 */
class AssoTagPostExFixture extends ActiveFixture
{
	public $modelClass = 'app\models\tag\AssoTagPost';

	public $dataFile   = '/app/tests/_data/tag/asso_tag_post.php';

	public $depends = [
		'app\modules\v1\admin\tests\_support\_fixtures\PostExFixture',
		'app\modules\v1\admin\tests\_support\_fixtures\TagExFixture',
	];
}
