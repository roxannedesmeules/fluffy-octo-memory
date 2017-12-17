<?php
namespace app\tests\_support\_fixtures;

use Faker\Factory;
use yii\test\ActiveFixture;

/**
 * Class CategoryLangFixture
 *
 * @package app\tests\_support\_fixtures
 */
class CategoryLangFixture extends ActiveFixture
{
	public $modelClass = 'app\models\category\CategoryLang';

	public $dataFile   = '/app/tests/_data/category_lang.php';

	public $depends    = [
		'app\tests\_support\_fixtures\CategoryFixture',
	];

	public static function build ( $cat, $lang, $valid = true )
	{
		$faker = Factory::create();

		return [
			"category_id" => $cat,
			"lang_id"     => $lang,
			"name"        => ($valid) ? $faker->text() : \Yii::$app->getSecurity()->generateRandomString(256),
			"slug"        => ($valid) ? $faker->slug() : \Yii::$app->getSecurity()->generateRandomString(256),
		];
	}
}
