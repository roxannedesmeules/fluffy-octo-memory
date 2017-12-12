<?php
$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
	'id'         => 'basic-tests',
	'basePath'   => dirname(__DIR__),

	'aliases'    => [
		'@bower'  => '@vendor/bower-asset',
		'@npm'    => '@vendor/npm-asset',
		"@v1"     => "/app/modules/v1",
		"@models" => "/app/models",
		"@upload" => "/app/web/upload",
	],

	// set target language to be Russian
	'language'       => 'en-CA',

	// set source language to be English
	'sourceLanguage' => 'en-CA',

	"modules"    => [
		'v1' => [
			'class' => 'app\modules\v1\module',
		],
	],

	'components' => [
		'db'           => $db,
		'mailer'       => [
			'useFileTransport' => true,
		],
		'assetManager' => [
			'basePath' => __DIR__ . '/../web/assets',
		],
		'urlManager'   => [
			'showScriptName' => true,
		],
		'user'         => [
			'identityClass' => 'app\models\user\User',
		],
		'request'      => [
			'cookieValidationKey'  => 'test',
			'enableCsrfValidation' => false,
		],
		'i18n'       => [
			'translations' => [
				"app*" => [
					"class" => 'yii\i18n\DbMessageSource',
				],
			],
		],
	],
	'params'     => $params,
];
