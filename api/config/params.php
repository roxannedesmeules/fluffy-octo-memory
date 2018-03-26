<?php

return [
	'adminEmail' => 'admin@example.com',

	"domainName" => "http://api.blog.local",

	"cors" => [
		"origin" => [
			"http://localhost:10100",   // admin panel
			"http://localhost:10110",   // blog
		],
	],
];
