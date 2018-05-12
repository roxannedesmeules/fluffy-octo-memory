<?php

namespace app\modules\v1\models;

use app\models\category\Category;
use app\models\category\CategoryLang;

/**
 * Class CategoryEx
 *
 * @package app\modules\v1\models
 *
 * @property CategoryLang $categoryLang
 */
class CategoryEx extends Category
{
	public function getCategoryLang ()
	{
		return $this->hasOne(CategoryLang::className(), [ "category_id" => "id" ])
		            ->andWhere([ "lang_id" => LangEx::getIdFromIcu(\Yii::$app->language) ]);
	}

	/** @inheritdoc */
	public function fields ()
	{
		return [
			"id",
			"name" => function ( self $model ) { return ($model->categoryLang) ? $model->categoryLang->name : ""; },
			"slug" => function ( self $model ) { return ($model->categoryLang) ? $model->categoryLang->slug : ""; },
		];
	}

	/**
	 * @return \app\models\category\CategoryBase[]|array
	 */
	public static function getAllWithLanguage ( )
	{
		return self::find()
		           ->active()
		           ->with("categoryLang")
		           ->all();
	}

	/**
	 * @param $categoryId
	 *
	 * @return \app\models\category\CategoryBase[]|array
	 */
	public static function getOneWithLanguage ( $categoryId )
	{
		return self::find()
		           ->id($categoryId)
		           ->active()
		           ->with("categoryLang")
		           ->all();
	}
}
