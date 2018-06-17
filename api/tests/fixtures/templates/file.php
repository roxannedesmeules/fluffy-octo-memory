<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
	"id"         => ($index + 1),
	"name"       => $faker->words($faker->numberBetween(1, 5), true),
	"path"       => $faker->imageUrl(),
	"created_on" => $faker->dateTimeThisMonth()->format("Y-m-d H:i:s"),
	"is_deleted" => (int) $faker->boolean(40),
];