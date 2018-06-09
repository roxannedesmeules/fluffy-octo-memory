<?php

namespace app\modules\v1\models;

use app\models\category\Category;
use app\models\category\CategoryLang;
use app\models\post\PostStatus;
use app\modules\v1\models\post\PostEx;

/**
 * Class CategoryEx
 *
 * @package app\modules\v1\models
 *
 * @property CategoryLang $categoryLang
 *
 * @SWG\Definition(
 *     definition = "Category",
 *
 *     @SWG\Property( property = "id",   type = "integer" ),
 *     @SWG\Property( property = "name", type = "string" ),
 *     @SWG\Property( property = "slug", type = "string" ),
 * )
 *
 * @SWG\Definition(
 *     definition = "CategoryCount",
 *
 *     @SWG\Property( property = "id",    type = "integer" ),
 *     @SWG\Property( property = "count", type = "integer" ),
 * )
 */
class CategoryEx extends Category
{
	public function getCategoryLang ()
	{
		return $this->hasOne(CategoryLang::className(), [ "category_id" => "id" ])
		            ->andWhere([ "lang_id" => LangEx::getIdFromIcu(\Yii::$app->language) ]);
	}

	/** @inheritdoc */
	public function getPostCount ()
	{
		return $this->hasOne(PostEx::className(), [ "category_id" => "id" ])
		            ->andWhere([ "post_status_id" => PostStatus::PUBLISHED ])
		            ->count();
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
	 * @return array
	 */
	public function countPostsByCategories ()
	{
		$list   = self::find()->active()->all();
		$result = [];

		foreach ($list as $category) {
			array_push($result, [ "id" => $category->id, "count" => (int) $category->postCount ]);
		}

		return $result;
	}

	/**
	 * Find all categories marked as active with the translation in the application language. It will then be mapped
	 * properly according to fields defined.
	 *
	 * @return self[]|array
	 */
	public static function getAllWithLanguage ( )
	{
		return self::find()
		           ->active()
		           ->with("categoryLang")
		           ->all();
	}

	/**
	 * @param integer $categorySlug
	 *
	 * @return self
	 */
	public static function getOneWithLanguage ( $categorySlug )
	{
		return self::find()
		           ->withSlug($categorySlug)
		           ->active()
		           ->with("categoryLang")
		           ->one();
	}

	/**
	 * @param int $categorySlug
	 *
	 * @return bool
	 */
	public static function slugExists ( $categorySlug )
	{
		return self::find()->withSlug($categorySlug)->active()->exists();
	}
}
