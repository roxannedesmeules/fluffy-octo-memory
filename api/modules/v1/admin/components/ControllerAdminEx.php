<?php
namespace app\modules\v1\admin\components;

use app\helpers\ArrayHelperEx;
use app\helpers\ParamsHelper;
use app\modules\v1\admin\components\security\ApiClientSecurity;
use app\modules\v1\admin\components\security\ApiTokenSecurity;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;
use yii\rest\OptionsAction;
use yii\web\Request;
use yii\web\Response;

/**
 * Class ControllerEx
 *
 * @package app\modules\v1\admin\components
 */
class ControllerAdminEx extends Controller
{
	/** @var  Request */
	public $request;

	/** @var  Response */
	public $response;

	/** @var array  */
	public $corsMethods = [ "OPTIONS", "GET", "POST", "PUT", "DELETE" ];
	
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
			"ClientToken"   => ApiClientSecurity::className(),
			"Authenticator" => [
				"class" => HttpBasicAuth::className(),
				"except" => [ "options" ],
			]
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
			"create" => [ "OPTIONS", "POST" ],
			"update" => [ "OPTIONS", "PUT" ],
			"delete" => [ "OPTIONS", "DELETE" ],
		];
	}

	protected function createdResult ( $result )
	{
		$this->response->setStatusCode(201);

		return $result;
	}

	protected function emptySuccess ()
	{
		$this->response->setStatusCode(204);
	}

	protected function error ( $code, $error )
	{
		$this->response->setStatusCode($code);

		return [ "message" => $error ];
	}

	protected function unprocessableResult ( $errors )
	{
		$this->response->setStatusCode(422);

		return $errors;
	}
}