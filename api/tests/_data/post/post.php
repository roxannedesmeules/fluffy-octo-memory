<?php

$faker = \Faker\Factory::create();

return [
	"draft"       => [ "category_id" => 1, "post_status_id" => 1, ],
	"archived"    => [ "category_id" => 1, "post_status_id" => 4, ],
	"published"   => [ "category_id" => 2, "post_status_id" => 3, ],
	"unpublished" => [ "category_id" => 2, "post_status_id" => 2, ],
];