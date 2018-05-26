<?php

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';
$rules  = require __DIR__ . "/url_rules.php";

$config = [
	'id'             => 'basic',
	'basePath'       => dirname(__DIR__),
	'bootstrap'      => [
		'log' => [
			"class"   => \yii\filters\ContentNegotiator::className(),
			"formats" => [
				//  comment next line to use GII
				'application/json' => \yii\web\Response::FORMAT_JSON,
			],
		],
	],

	// set target language to be Russian
	'language'       => 'en-CA',

	// set source language to be English
	'sourceLanguage' => 'en-CA',

	'aliases'    => [
		'@bower'  => '@vendor/bower-asset',
		'@npm'    => '@vendor/npm-asset',
		"@v1"     => "/app/modules/v1",
		"@models" => "/app/models",
		"@upload" => "/app/web/upload",
	],
	"modules"    => [
		'v1' => [
			'class' => 'app\modules\v1\module',
		],
	],
	'components' => [
		'db'         => $db,
		'request'    => [
			"enableCookieValidation" => false,
			"enableCsrfValidation"   => false,
			"parsers"                => [
				"application/json" => 'yii\web\JsonParser',
			],
		],
		'cache'      => [
			'class' => 'yii\caching\FileCache',
		],
		'user'       => [
			'identityClass'   => 'app\models\user\User',
			'enableAutoLogin' => true,
			'enableSession'   => false,
			'loginUrl'        => null,
		],
		'mailer'     => [
			'class'            => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log'        => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => [ 'error', 'warning' ],
				],
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
		//  Comment whole response block to use Gii
		"response"   => [
			"class"         => \yii\web\Response::className(),
			"on beforeSend" => function ( $event ) {
				/** @var \yii\web\Response $response */
				$response = $event->sender;

				if ( !is_null($response->data) ) {
					if (!$response->getIsSuccessful()) {
						$data = [ "code" => $response->getStatusCode(), ];

						if (isset($response->data[ "error" ])) {
							$data[ "error" ] = $response->data[ "error" ];
						}

						if (isset($response->data[ "message" ])) {
							$data[ "message" ] = $response->data[ "message" ];
						}
					}
				}
			},
		],
	],
	'params'     => $params,
];

if ( YII_ENV_DEV ) {
	// configuration adjustments for 'dev' environment
	$config[ 'bootstrap' ][]        = 'debug';
	$config[ 'modules' ][ 'debug' ] = [
		'class' => 'yii\debug\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];

	$config[ 'bootstrap' ][]      = 'gii';
	$config[ 'modules' ][ 'gii' ] = [
		'class'      => 'yii\gii\Module',
		// uncomment the following to add your IP if you are not connecting from localhost.
		'allowedIPs' => [ '*' ],
	];
}

return $config;
