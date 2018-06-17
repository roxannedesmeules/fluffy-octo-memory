<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
	"post_id"    => $faker->numberBetween(1, 10),
	"lang_id"    => $faker->randomElement(array_column(\app\models\app\Lang::find()->all(), "id")),
	"user_id"    => 1,
	"title"      => $faker->text(),
	"slug"       => $faker->slug(),
	"summary"    => $faker->text(180),
	"content"    => $faker->paragraphs(3, true),
	"file_id"    => null,
	"file_alt"   => null,
	"created_on" => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
	"updated_on" => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
];