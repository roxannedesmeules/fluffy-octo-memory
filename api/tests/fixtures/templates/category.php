<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
	"is_active"  => (int) $faker->boolean(),
	"created_on" => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
	"updated_on" => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
];