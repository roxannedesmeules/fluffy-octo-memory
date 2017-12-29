<?php

$faker = \Faker\Factory::create();

return [
	"post1-fr" => [
		"post_id"    => 1,
		"lang_id"    => \app\models\app\Lang::FR,
		"user_id"    => 1,
		"title"      => $faker->text(),
		"slug"       => $faker->slug(),
		"content"    => $faker->paragraphs(3, true),
	],
	"post1-en" => [
		"post_id"    => 1,
		"lang_id"    => \app\models\app\Lang::EN,
		"user_id"    => 1,
		"title"      => $faker->text(),
		"slug"       => $faker->slug(),
		"content"    => $faker->paragraphs(rand(1, 10), true),
	],
	"post2-en" => [
		"post_id"    => 2,
		"lang_id"    => \app\models\app\Lang::EN,
		"user_id"    => 1,
		"title"      => $faker->text(),
		"slug"       => $faker->slug(),
		"content"    => $faker->paragraphs(rand(1, 10), true),
	],
	"post3-en" => [
		"post_id"    => 3,
		"lang_id"    => \app\models\app\Lang::EN,
		"user_id"    => 1,
		"title"      => $faker->text(),
		"slug"       => $faker->slug(),
		"content"    => $faker->paragraphs(rand(1, 10), true),
	],
	"post3-fr" => [
		"post_id"    => 3,
		"lang_id"    => \app\models\app\Lang::FR,
		"user_id"    => 1,
		"title"      => $faker->text(),
		"slug"       => $faker->slug(),
		"content"    => $faker->paragraphs(rand(1, 10), true),
	],
];