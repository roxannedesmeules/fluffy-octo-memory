<?php
namespace app\modules\v1\admin\components;

use app\helpers\ArrayHelperEx;
use app\modules\v1\admin\components\security\ApiClientSecurity;
use app\modules\v1\admin\components\security\ApiTokenSecurity;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\Controller;

/**
 * Class ControllerEx
 *
 * @package app\modules\v1\admin\components
 */
class ControllerAdminEx extends Controller
{
	public $request;
	public $response;
	
	public function init ()
	{
		parent::init();
		
		$this->request  = \Yii::$app->request;
		$this->response = \Yii::$app->response;
	}
	
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(), [
			"ClientToken"   => ApiClientSecurity::className(),
			"Authenticator" => HttpBasicAuth::className(),
		]);
	}
	
	public function actionOptions () { }
}