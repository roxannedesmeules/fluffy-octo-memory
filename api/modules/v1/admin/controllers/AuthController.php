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

	protected function verbs ()
	{
		return [
			"login"  => [ "OPTIONS", "POST" ],
			"logout" => [ "OPTIONS", "DELETE" ],
		];
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

	/**
	 * @SWG\Post(
	 *     path     = "/v1/admin/auth",
	 *     tags     = { "Authentication" },
	 *     summary  = "Login",
	 *     description = "Authenticate a user to have access to the admin panel",
	 *     security    = { "ApiClientSecurity" },
	 *
	 *     @SWG\Parameter( name = "user", in = "body", @SWG\Schema( ref = "#/definitions/UserAuth" ),),
	 *
	 *     @SWG\Response( response = 200, description = "authenticated user", @SWG\Schema( ref = "#/definitions/User" ), ),
	 *     @SWG\Response( response = 400, description = "wrong user/password or inactive", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "invalid data for authentication", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 * )
	 */
	public function actionLogin ()
	{
		$form = new UserAuth();
		$form->attributes = $this->request->getBodyParams();
		
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

	/**
	 * @SWG\Delete(
	 *     path    = "/v1/admin/auth",
	 *     tags    = { "Authentication" },
	 *     summary = "Logout",
	 *     description = "Invalidate authenticated user to force her to login again",
	 *     
	 *     @SWG\Response( response = 204, description = "user was correctly logout" ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
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