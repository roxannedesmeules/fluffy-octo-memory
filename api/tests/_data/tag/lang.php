<?php

$faker = \Faker\Factory::create();

return [
	"1-fr" => [
		"tag_id"  => 1,
		"lang_id" => \app\models\app\Lang::FR,
		"name"    => $faker->text(),
		"slug"    => $faker->slug(),
	],
	"1-en" => [
		"tag_id"  => 1,
		"lang_id" => \app\models\app\Lang::EN,
		"name"    => $faker->text(),
		"slug"    => $faker->slug(),
	],
	"2-fr" => [
		"tag_id"  => 2,
		"lang_id" => \app\models\app\Lang::FR,
		"name"    => $faker->text(),
		"slug"    => $faker->slug(),
	],
	"3-en" => [
		"tag_id"  => 3,
		"lang_id" => \app\models\app\Lang::EN,
		"name"    => $faker->text(),
		"slug"    => $faker->slug(),
	],
	"5-en" => [
		"tag_id"  => 5,
		"lang_id" => \app\models\app\Lang::EN,
		"name"    => $faker->text(),
		"slug"    => $faker->slug(),
	],
	"5-fr" => [
		"tag_id"  => 5,
		"lang_id" => \app\models\app\Lang::FR,
		"name"    => $faker->text(),
		"slug"    => $faker->slug(),
	],
];