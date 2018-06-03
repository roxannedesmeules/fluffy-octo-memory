<?php
namespace app\modules\v1\components;

use app\helpers\ArrayHelperEx;
use app\helpers\ParamsHelper;
use app\modules\v1\components\headers\Language;
use app\modules\v1\components\security\ApiClientSecurity;
use yii\rest\Controller;
use yii\rest\OptionsAction;
use yii\web\Request;
use yii\web\Response;

/**
 * Class ControllerEx
 *
 * @package app\modules\v1\components
 */
class ControllerEx extends Controller
{
	/** @var  Request */
	public $request;

	/** @var  Response */
	public $response;

	/** @var array  */
	public $corsMethods = [ "OPTIONS", "GET" ];
	
	public function init ()
	{
		parent::init();
		
		$this->request  = \Yii::$app->request;
		$this->response = \Yii::$app->response;
	}
	
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(), [
			"corsFilter"    => [
				"class" => \yii\filters\Cors::className(),
				"cors"  => [
					"Origin"                         => ParamsHelper::get("cors.origin"),
					"Access-Control-Allow-Origin"    => ParamsHelper::get("cors.origin"),
					"Access-Control-Request-Method"  => $this->corsMethods,
					"Access-Control-Max-Age"         => 3600,
					"Access-Control-Request-Headers" => [ "*" ],
					'Access-Control-Allow-Credentials' => true,
					"Access-Control-Expose-Headers"    => [
						"X-Pagination-Current-Page",
						"X-Pagination-Page-Count",
						"X-Pagination-Per-Page",
						"X-Pagination-Total-Count",
					],
				],
			],
			"Language"    => Language::className(),
			"ClientToken" => ApiClientSecurity::className(),
		]);
	}

	/** @inheritdoc */
	public function actions ()
	{
		return ArrayHelperEx::merge(parent::actions(), [ "options" => OptionsAction::className(), ]);
	}

	/** @inheritdoc */
	protected function verbs ()
	{
		return [
			"index"  => [ "OPTIONS", "GET" ],
			"view"   => [ "OPTIONS", "GET" ],
		];
	}

	public function notFound ( $message = "" )
	{
		$this->response->setStatusCode(404);

		return [ "message" => $message ];
	}
}