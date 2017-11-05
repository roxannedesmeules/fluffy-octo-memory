<?php
namespace app\modules\v1\admin\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use Yii;

/**
 * Class DefaultController
 *
 * @package app\modules\v1\admin\controllers
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
					Yii::getAlias("@v1/admin/swagger"),
					Yii::getAlias("@v1/admin/controllers"),
					Yii::getAlias("@v1/admin/models"),
					Yii::getAlias("@models"),
				],
			],
		];
	}
}
