<?php
namespace app\modules\v1\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use Yii;

/**
 * Class DefaultController
 *
 * @package app\modules\v1\controllers
 */
class DefaultController extends Controller
{
	public function actions ()
	{
		return [
			"doc" => [
				"class"   => \light\swagger\SwaggerAction::className(),
				"restUrl" => Url::to([ "api" ], true),
			],
			"api" => [
				"class"   => \light\swagger\SwaggerApiAction::className(),
				"scanDir" => [
					Yii::getAlias("@v1/swagger"),
					Yii::getAlias("@v1/controllers"),
					Yii::getAlias("@v1/models"),
					Yii::getAlias("@models"),
				],
			],
		];
	}
}
