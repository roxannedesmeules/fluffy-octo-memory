<?php
namespace app\modules\v1\components\security;

use app\helpers\ArrayHelperEx;
use app\models\app\ApiClient;
use yii\base\Behavior;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Class ApiClientSecurity
 *
 * @package app\modules\v1\components\security
 */
class ApiClientSecurity extends Behavior
{
	/** @inheritdoc */
	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(), [
			Controller::EVENT_BEFORE_ACTION => "checkApiClient",
		]);
	}
	
	/**
	 * @param $event
	 *
	 * @throws ForbiddenHttpException
	 */
	public function checkApiClient ( $event )
	{
		if ($event->action->id === "options") {
			return;
		}

		//  get request headers
		$headers = \Yii::$app->request->getHeaders();
		
		//  get API Client key
		$key = $headers->get("Api-Client");

		//  if key isn't set, throw error
		if ( empty($key) || is_null($key) ) {
			throw new ForbiddenHttpException("MISSING_API_CLIENT_KEY");
		}
		
		//  check if API Client key is valid
		$apiClient = ApiClient::findBlogKey($key);
		
		if ( is_null($apiClient) ) {
			throw new ForbiddenHttpException("INVALID_API_CLIENT_KEY");
		}
	}
}