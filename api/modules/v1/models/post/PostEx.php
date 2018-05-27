<?php

namespace app\modules\v1\models\post;

use app\helpers\DateHelper;
use app\models\post\Post;
use app\models\post\PostLangQuery;
use app\modules\v1\models\CategoryEx;
use app\modules\v1\models\LangEx;

/**
 * Class PostEx
 *
 * @package app\modules\v1\models
 */
class PostEx extends Post
{
	public function getPostLangs ()
	{
		return $this->hasMany(PostLangEx::className(), [ "post_id" => "id" ]);
	}

	/** @inheritdoc */
	public function getCategory ()
	{
		return $this->hasOne(CategoryEx::className(), [ "id"  => "category_id" ]);
	}

	/** @inheritdoc */
	public function fields ()
	{
		return [
			"id",
			"category" => "category",
			"title"    => function ( self $model ) {
				return PostLangEx::getTranslationTitle($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"slug"     => function ( self $model ) {
				return PostLangEx::getTranslationSlug($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"summary"  => function ( self $model ) {
				return PostLangEx::getTranslationSummary($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"content"  => function ( self $model ) {
				return PostLangEx::getTranslationContent($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"cover"    => function ( self $model ) {
				return [
					"url" => PostLangEx::getTranslationCoverPath($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language)),
					"alt" => PostLangEx::getTranslationCoverAlt($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language)),
				];
			},
			"author"   => function ( self $model ) {
				   return PostLangEx::getTranslationAuthor($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"published_on" => function ( self $model ) { return DateHelper::formatDate($model->published_on, DateHelper::DATE_FORMAT); },
		];
	}

	/**
	 * @return \app\models\post\Post[]|array
	 */
	public static function getAllWithLanguage ()
	{
		return self::find()
		           ->isPublished()
		           ->withTranslationIn(LangEx::getIdFromIcu(\Yii::$app->language))
		           ->all();
	}

	public static function getOneBySlugWithLanguage ($slug)
	{
		return self::find()
					->isPublished()
					->joinWith([
						"postLangs" => function ( PostLangQuery $translation ) use ( $slug ) {
							return $translation->bySlug($slug)
							                   ->byLang(LangEx::getIdFromIcu(\Yii::$app->language));
						},
					])
					->one();
	}
}
