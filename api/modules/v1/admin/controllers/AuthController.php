<?php
namespace app\modules\v1\admin\controllers;

use app\helpers\ArrayHelperEx;
use app\helpers\ParamsHelper;
use app\modules\v1\admin\components\security\ApiClientSecurity;
use app\modules\v1\admin\models\user\forms\UserAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;
use yii\rest\OptionsAction;
use yii\web\BadRequestHttpException;
use yii\web\Request;
use yii\web\Response;

/**
 * Class SessionController
 *
 * @package app\modules\v1\admin\controllers
 */
class AuthController extends Controller
{
	/** @var  Request */
	public $request;

	/** @var  Response */
	public $response;
	
	public function init ()
	{
		parent::init();
		
		$this->request  = \Yii::$app->request;
		$this->response = \Yii::$app->response;
	}
	
	public function behaviors ()
	{
		return [
			"corsFilter"    => [
				"class" => \yii\filters\Cors::className(),
				"cors"  => [
					"Origin"                         => ParamsHelper::get("cors.origin"),
					"Access-Control-Allow-Origin"    => ParamsHelper::get("cors.origin"),
					"Access-Control-Request-Method"  => [ "OPTIONS", "POST", "DELETE" ],
					"Access-Control-Max-Age"         => 3600,
					"Access-Control-Request-Headers" => [ "*" ],
					'Access-Control-Allow-Credentials' => true,
				],
			],
			"ClientToken"   => ApiClientSecurity::className(),
			"Authenticator" => [
				"class"  => HttpBasicAuth::className(),
				"except" => [ "login", "options" ],
			],
		];
	}

	public function actions ()
	{
		return ArrayHelperEx::merge(parent::actions(), [ "options" => OptionsAction::className(), ]);
	}

	public function actionLogin ()
	{
		$form = new UserAuth();
		$form->attributes = $this->request->getBodyParams();

		var_dump($this->request->getBodyParams()); die();

		var_dump($form->attributes); die();
		
		if ( !$form->validate() ) {
			$this->response->setStatusCode(422);
			
			return $form->getErrors();
		}
		
		$result = $form->authenticate();
		
		switch ( $result[ "status" ] ) {
			case UserAuth::ERROR :
				throw new BadRequestHttpException($result[ "error" ]);
				
			case UserAuth::SUCCESS :
				return $result[ "user" ];
		}
	}
	
	public function actionLogout ()
	{
		$form = new UserAuth();
		
		$result = $form->logout(\Yii::$app->user->getId());
		
		switch ( $result[ "status" ] ) {
			case UserAuth::ERROR :
				break;
				
			case UserAuth::SUCCESS :
				$this->response->setStatusCode(204);
				break;
		}
	}
}