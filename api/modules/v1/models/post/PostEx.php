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
 *
 * @property \app\modules\v1\models\post\PostLangEx $postLang
 */
class PostEx extends Post
{
	const SCENARIO_DEFAULT  = "partial";
	const SCENARIO_COMPLETE = "complete";

	/**  */
	public function getPostLang ()
	{
		return $this->hasOne(PostLangEx::className(), [ "post_id" => "id" ])
		            ->byLang(LangEx::getIdFromIcu(\Yii::$app->language));
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
			"category"        => "category",
			"featured"        => "is_featured",
			"comment_enabled" => "is_comment_enabled",
			"title"           => function ( self $model ) { return $model->postLang->title; },
			"slug"            => function ( self $model ) { return $model->postLang->slug; },
			"summary"         => function ( self $model ) { return $model->postLang->summary; },
			"cover"           => function ( self $model ) {
				return [
					"url" => (isset($model->postLang->file)) ? $model->postLang->file->getFullPath() : "",
					"alt" => $model->postLang->file_alt,
				];
			},
			"author"          => function ( self $model ) { return $model->postLang->user; },
			"comments"        => function ( self $model ) { return [ "count" => $model->postLang->getCommentsCount() ]; },
			"published_on"    => function ( self $model ) { return DateHelper::formatDate($model->published_on, DateHelper::DATE_FORMAT); },
		];

		switch ($this->getScenario()) {
			case self::SCENARIO_COMPLETE :
				return ArrayHelperEx::merge($fields, [
					"content"  => function ( self $model ) { return $model->postLang->content; },
					"comments" => function ( self $model ) {
						return [
							"count" => $model->postLang->getCommentsCount(),
							"list"  => $model->postLang->getCommentsTree(),
						];
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
		           ->with("postLang")
		           ->orderPublication();

		if (!is_null($filters[ "category" ])) {
			$query->category($filters[ "category" ]);
		}

		if (!is_null($filters[ "tag" ])) {
			$query->withTag($filters[ "tag" ]);
		}

		if (!is_null($filters[ "featured" ])) {
			$query->featured($filters[ "featured" ]);
		}

		return $query->all();
	}

	public static function getOneByIdWithLanguage ( $postId )
	{
		$post = self::find()
		            ->isPublished()
		            ->id($postId)
		            ->joinWith("postLang")
		            ->one();

		$post->setScenario(self::SCENARIO_COMPLETE);

		return $post;
	}

	public static function getOneBySlugWithLanguage ($slug)
	{
		$post = self::find()
					->isPublished()
					->joinWith([
						"postLang" => function ( PostLangQuery $query ) use ( $slug ) {
							return $query->bySlug($slug);
						},
					])
					->one();

		$post->setScenario(self::SCENARIO_COMPLETE);

		return $post;
	}
}
