<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;
use Faker\Factory;

/**
 * Class CategoryLangFixture
 *
 * @package app\tests\unit\fixtures
 */
class CategoryLangFixture extends ActiveFixture
{
	public $modelClass = 'app\models\category\CategoryLang';

	public $dataFile   = "@tests/fixtures/data/category_lang.php";

	public $depends = [
		'app\tests\fixtures\CategoryFixture',
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
