<?php

$faker = \Faker\Factory::create();

return [
	"draft-fr"     => [
		"post_id" => 1,
		"lang_id" => \app\models\app\Lang::FR,
		"user_id" => 1,
		"title"   => $faker->text(),
		"slug"    => $faker->slug(),
		"content" => $faker->paragraphs(3, true),
	],
	"draft-en"     => [
		"post_id" => 1,
		"lang_id" => \app\models\app\Lang::EN,
		"user_id" => 1,
		"title"   => $faker->text(),
		"slug"    => $faker->slug(),
		"content" => $faker->paragraphs(rand(1, 10), true),
	],
	"archived-en"  => [
		"post_id" => 2,
		"lang_id" => \app\models\app\Lang::EN,
		"user_id" => 1,
		"title"   => $faker->text(),
		"slug"    => $faker->slug(),
		"content" => $faker->paragraphs(rand(1, 10), true),
	],
	"published-en" => [
		"post_id" => 3,
		"lang_id" => \app\models\app\Lang::EN,
		"user_id" => 1,
		"title"   => $faker->text(),
		"slug"    => $faker->slug(),
		"content" => $faker->paragraphs(rand(1, 10), true),
	],
	"published-fr" => [
		"post_id" => 3,
		"lang_id" => \app\models\app\Lang::FR,
		"user_id" => 1,
		"title"   => $faker->text(),
		"slug"    => $faker->slug(),
		"content" => $faker->paragraphs(rand(1, 10), true),
	],
];