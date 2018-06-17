<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FileFixture
 *
 * @package app\tests\fixtures
 */
class FileFixture extends ActiveFixture
{
	public $modelClass = \app\models\app\File::class;

	public $dataFile   = "@tests/fixtures/data/file.php";
}
