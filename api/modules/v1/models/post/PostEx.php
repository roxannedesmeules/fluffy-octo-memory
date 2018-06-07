<?php

namespace app\modules\v1\models\post;

use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;
use app\models\post\Post;
use app\models\post\PostLangQuery;
use app\modules\v1\models\CategoryEx;
use app\modules\v1\models\LangEx;
use app\modules\v1\models\TagEx;

/**
 * Class PostEx
 *
 * @package app\modules\v1\models
 */
class PostEx extends Post
{
	const SCENARIO_DEFAULT  = "partial";
	const SCENARIO_COMPLETE = "complete";

	/** @inheritdoc */
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
	public function getTags ()
	{
		return $this->hasMany(TagEx::className(), [ 'id' => 'tag_id' ])
		            ->viaTable('asso_tag_post', [ 'post_id' => 'id' ]);
	}

	/** @inheritdoc */
	public function fields ()
	{
		$fields = [
			"id",
			"category"     => "category",
			"title"        => function ( self $model ) {
				return PostLangEx::getTranslationTitle($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"slug"         => function ( self $model ) {
				return PostLangEx::getTranslationSlug($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"summary"      => function ( self $model ) {
				return PostLangEx::getTranslationSummary($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"cover"        => function ( self $model ) {
				return [
					"url" => PostLangEx::getTranslationCoverPath($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language)),
					"alt" => PostLangEx::getTranslationCoverAlt($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language)),
				];
			},
			"author"       => function ( self $model ) {
				return PostLangEx::getTranslationAuthor($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
			},
			"published_on" => function ( self $model ) { return DateHelper::formatDate($model->published_on, DateHelper::DATE_FORMAT); },
		];

		switch ($this->getScenario()) {
			case self::SCENARIO_COMPLETE :
				return ArrayHelperEx::merge($fields, [
					"content" => function ( self $model ) {
						return PostLangEx::getTranslationContent($model->postLangs, LangEx::getIdFromIcu(\Yii::$app->language));
					},
					"tags",
				]);

			case self::SCENARIO_DEFAULT :
			default :
				return $fields;
		}
	}

	/**
	 * @param array $filters
	 *
	 * @return self []|array
	 */
	public static function getAllWithLanguage ( $filters )
	{
		$query = self::find()
		           ->isPublished()
		           ->withTranslationIn(LangEx::getIdFromIcu(\Yii::$app->language))
		           ->orderPublication();

		if (!is_null($filters[ "category" ])) {
			$query->category($filters[ "category" ]);
		}

		return $query->all();
	}

	public static function getOneBySlugWithLanguage ($slug)
	{
		$post = self::find()
					->isPublished()
					->joinWith([
						"postLangs" => function ( PostLangQuery $translation ) use ( $slug ) {
							return $translation->bySlug($slug)
							                   ->byLang(LangEx::getIdFromIcu(\Yii::$app->language));
						},
					])
					->one();

		$post->setScenario(self::SCENARIO_COMPLETE);

		return $post;
	}
}
