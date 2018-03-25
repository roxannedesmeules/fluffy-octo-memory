<?php

return [
	"" => "site",

	//  V1 rules
	"v1/doc" => "v1/default/doc",
	"v1/api" => "v1/default/api",

	//  V1 Admin rules
	"v1/admin/doc" => "v1/admin/default/doc",
	"v1/admin/api" => "v1/admin/default/api",

	"OPTIONS v1/admin/auth" => "v1/admin/auth/options",
	"POST    v1/admin/auth" => "v1/admin/auth/login",
	"DELETE  v1/admin/auth" => "v1/admin/auth/logout",

	[ "class" => 'yii\rest\UrlRule', "controller" => [ "v1/admin/category" ] ],
	[ "class" => 'yii\rest\UrlRule', "controller" => [ "v1/admin/post" ] ],
	[
		"class"      => 'yii\rest\UrlRule',
		"controller" => [ "v1/admin/language" ],
		"except"     => [ "view", "create", "update", "delete" ],
	],
	[
		"class"      => 'yii\rest\UrlRule',
		"controller" => [ "v1/admin/posts/statuses" => "v1/admin/post-status" ],
		"except"     => [ "view", "create", "update", "delete" ],
	],
	[
		"class"         => 'yii\rest\UrlRule',
		"controller"    => [ "v1/admin/user/me" => "v1/admin/user-profile" ],
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