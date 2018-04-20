<?php

$admin = "v1/admin";
$int   = "\\d[\\d,]*";

return [
	"" => "site",

	//  V1 rules
	"v1/doc" => "v1/default/doc",
	"v1/api" => "v1/default/api",

	//  V1 Admin rules
	"$admin/doc" => "$admin/default/doc",
	"$admin/api" => "$admin/default/api",

	"OPTIONS $admin/auth" => "$admin/auth/options",
	"POST    $admin/auth" => "$admin/auth/login",
	"DELETE  $admin/auth" => "$admin/auth/logout",

	//  categories
	[ "class" => 'yii\rest\UrlRule', "controller" => [ "$admin/category" ] ],

	//  languages
	[
		"class"      => 'yii\rest\UrlRule',
		"controller" => [ "$admin/language" ],
		"except"     => [ "view", "create", "update", "delete" ],
	],

	//  posts
	[ "class" => 'yii\rest\UrlRule', "controller" => [ "$admin/posts" => "$admin/post/post" ], ],

	//  posts translation cover
	"OPTIONS $admin/posts/<postId:$int>/<langId:$int>/cover" => "$admin/post/cover/options",
	"POST    $admin/posts/<postId:$int>/<langId:$int>/cover" => "$admin/post/cover/create",
	"DELETE  $admin/posts/<postId:$int>/<langId:$int>/cover" => "$admin/post/cover/delete",

	//  post statuses
	[
		"class"      => 'yii\rest\UrlRule',
		"controller" => [ "$admin/posts/statuses" => "$admin/post/status" ],
		"except"     => [ "view", "create", "update", "delete" ],
	],

	//  user profile
	[
		"class"         => 'yii\rest\UrlRule',
		"controller"    => [ "$admin/user/me" => "$admin/user/profile" ],
		'pluralize'     => false,
		"except"        => [ "index", "view", "create", "delete" ],
		"extraPatterns" => [
			"PUT password"     => "update-password",
			"OPTIONS password" => "options",
			"POST picture"     => "upload-picture",
			"OPTIONS picture"  => "options",
		],
		"tokens" => [],
	]
];