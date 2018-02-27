<?php
namespace app\tests\_support\_fixtures;

use Faker\Factory;
use yii\test\ActiveFixture;

/**
 * Class TagLangFixture
 *
 * @package app\tests\_support\_fixtures
 */
class TagLangFixture extends ActiveFixture
{
	public $modelClass = 'app\models\tag\TagLang';

	public $dataFile   = '/app/tests/_data/tag/lang.php';

	public $depends    = [
		// 'app\tests\_support\_fixtures\CategoryFixture',
	];

	public static function build ( $cat, $lang, $valid = true )
	{
		$faker = Factory::create();

		return [
			"tag_id"  => $cat,
			"lang_id" => $lang,
			"name"    => ($valid) ? $faker->text() : \Yii::$app->getSecurity()->generateRandomString(256),
			"slug"    => ($valid) ? $faker->slug() : \Yii::$app->getSecurity()->generateRandomString(256),
		];
	}
}
