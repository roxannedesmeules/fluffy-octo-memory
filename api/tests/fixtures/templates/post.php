<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$status    = $faker->randomElement(array_column(\app\models\post\PostStatus::find()->all(), "id"));
$published = $status == \app\models\post\PostStatus::PUBLISHED;

return [
	"id" => $index + 1,
	"category_id"    => $faker->numberBetween(1, 10),
	"post_status_id" => $status,
	"is_featured"    => (int) $faker->boolean(),
	"created_on"     => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
	"updated_on"     => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
	"published_on"   => ($published) ? $faker->dateTimeThisMonth()->format('Y-m-d H:i:s') : null,
];