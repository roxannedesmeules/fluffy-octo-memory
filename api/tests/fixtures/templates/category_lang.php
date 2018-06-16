<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
	"category_id" => $faker->numberBetween(1, 10),
	"lang_id"     => $faker->randomElement(array_column(\app\models\app\Lang::find()->all(), "id")),
	"name"        => implode(" ", $faker->words(6)),
	"slug"        => $faker->slug(),
];