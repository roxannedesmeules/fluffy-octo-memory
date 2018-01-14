<?php

$faker = \Faker\Factory::create();

return [
	"inactive-en" => [
		"category_id" => 1,
		"lang_id"     => 1,
		"name"        => $faker->text(),
		"slug"        => $faker->slug(),
	],
	"inactive-fr" => [
		"category_id" => 1,
		"lang_id"     => 2,
		"name"        => $faker->text(),
		"slug"        => $faker->slug(),
	],
	"active-fr"   => [
		"category_id" => 2,
		"lang_id"     => 2,
		"name"        => $faker->text(),
		"slug"        => $faker->slug(),
	],
	"nopost-fr" => [
		"category_id" => 4,
		"lang_id"     => 2,
		"name"        => $faker->text(),
		"slug"        => $faker->slug(),
	],
];