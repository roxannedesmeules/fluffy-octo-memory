<?php
$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/test_db.php';
$rules  = require __DIR__ . "/url_rules.php";

/**
 * Application configuration shared by all test types
 */
return [
	'id'         => 'basic-tests',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => [
		'log' => [
			"class"   => \yii\filters\ContentNegotiator::className(),
			"formats" => [
				//  comment next line to use GII
				'application/json' => \yii\web\Response::FORMAT_JSON,
			],
		],
	],

	'aliases'    => [
		'@bower'  => '@vendor/bower-asset',
		'@npm'    => '@vendor/npm-asset',
		"@v1"     => "/app/modules/v1",
		"@models" => "/app/models",
		"@upload" => "/app/web/upload",
		"@tests"  => "/app/tests",
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
		'user'         => [
			'identityClass' => 'app\models\user\User',
		],
		'request'    => [
			"enableCookieValidation" => false,
			"enableCsrfValidation"   => false,
			"parsers"                => [
				"application/json" => 'yii\web\JsonParser',
			],
		],
		'i18n'       => [
			'translations' => [
				"app*" => [
					"class" => 'yii\i18n\DbMessageSource',
				],
			],
		],
		'urlManager' => [
			'enablePrettyUrl'     => true,
			'enableStrictParsing' => true,
			'showScriptName'      => false,
			'rules'               => $rules,
		],
		"response"   => [
			"class"         => \yii\web\Response::className(),
			"format"        => \yii\web\Response::FORMAT_JSON,
			"on beforeSend" => function ( $event ) {
				/** @var \yii\web\Response $response */
				$response = $event->sender;

				if ( !is_null($response->data) ) {
					if ( !$response->getIsSuccessful() && array_key_exists("message", $response->data) ) {
						$response->data = [
							"code"    => $response->getStatusCode(),
							"message" => $response->data[ "message" ],
						];
					} else if ( !$response->getIsSuccessful() ) {
						$response->data = [
							"code"  => $response->getStatusCode(),
							"error" => $response->data,
						];
					}
				}
			},
		],
	],
	'params'     => $params,
];
