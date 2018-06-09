<?php

namespace app\modules\v1\models;

use app\models\tag\Tag;
use app\models\tag\TagLang;

/**
 * Class TagEx
 *
 * @package app\modules\v1\models
 *
 * @property TagLang $tagLang
 */
class TagEx extends Tag
{
	public function getTagLang ()
	{
		return $this->hasOne(TagLang::className(), [ "tag_id" => "id" ])
		            ->andWhere([ "lang_id" => LangEx::getIdFromIcu(\Yii::$app->language) ]);
	}

	/** @inheritdoc */
	public function fields ()
	{
		return [
			"id",
			"name" => function ( self $model ) { return $model->tagLang->name; },
			"slug" => function ( self $model ) { return $model->tagLang->slug; },
		];
	}

	public static function getAllWithLanguage ()
	{
		return self::find()
		           ->withPublishedPosts()
		           ->with("tagLang")
		           ->all();
	}

	public static function getOneWithLanguage ( $tagId ) {}

	/**
	 * This method will check if the slug passed in parameter exists for a Tag and that the Tag entry
	 * is linked to published posts.
	 *
	 * @param string $slug
	 *
	 * @return bool
	 */
	public static function slugExists ( $slug )
	{
		return self::find()->withSlug($slug)->withPublishedPosts()->exists();
	}
}
