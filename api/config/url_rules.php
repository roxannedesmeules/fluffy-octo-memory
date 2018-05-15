<?php

$admin = "v1/admin";
$int   = "\\d[\\d,]*";

return [
	""           => "site",

	//  V1 rules
	"v1/doc"     => "v1/default/doc",
	"v1/api"     => "v1/default/api",

	//  categories
	[ "class" => 'yii\rest\UrlRule', "controller" => [ "v1/categories" => "v1/category/category" ], ],
	[
		"class"         => 'yii\rest\UrlRule',
		"controller"    => [ "v1/categories/posts" => "v1/category/post" ],
		"except"        => [ "create", "update", "delete" ],
		"extraPatterns" => [ "GET count" => "count", "OPTIONS count" => "options", ],
	],

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

	//  tags
	[
		"class"      => 'yii\rest\UrlRule',
		"controller" => [ "$admin/tags" => "$admin/tag/tag" ],
	],

	//  post tags relation
	[
		"class"      => 'yii\rest\UrlRule',
		"controller" => [ "$admin/posts-tags" => "$admin/post/tag" ],
		"except"     => [ "index", "view", "update" ],
		"patterns"   => [
			'POST'   => 'create',
			'DELETE' => 'delete',
			''       => 'options',
		],
	],

	//  user profile
	"OPTIONS $admin/user/me"          => "$admin/user/profile/options",
	"OPTIONS $admin/user/me/password" => "$admin/user/profile/options",
	"OPTIONS $admin/user/me/picture"  => "$admin/user/profile/options",
	"PUT $admin/user/me"              => "$admin/user/profile/update",
	"PUT $admin/user/me/password"     => "$admin/user/profile/update-password",
	"POST $admin/user/me/picture"     => "$admin/user/profile/upload-picture",
];