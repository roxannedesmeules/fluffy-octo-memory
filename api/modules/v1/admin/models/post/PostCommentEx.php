<?php

namespace app\modules\v1\admin\models\post;

use app\helpers\DateHelper;
use app\models\post\PostComment;
use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\user\UserEx;

/**
 * Class PostCommentEx
 *
 * @package app\modules\v1\admin\models\post
 */
class PostCommentEx extends PostComment
{
	/** @inheritdoc */
	public function getReplies ()
	{
		return $this->hasMany(self::className(), [ "reply_comment_id" => "id" ]);
	}

	/** @inheritdoc */
	public function getUser ()
	{
		return $this->hasOne(UserEx::className(), [ "id" => "user_id" ]);
	}

	/** @inheritdoc */
	public function fields ()
	{
		return [
			"id",
			"post_id",
			"lang_id",
			"author" => function ( self $model ) {
				if (!is_null($model->user_id)) {
					return $model->user->profile->getFullname();
				} else {
					return $model->author;
				}
			},
			"user",
			"comment",
			"replies",
			"is_approved",
			"created_on"  => function ( self $model ) { return DateHelper::formatDate($model->created_on); },
			"approved_on" => function ( self $model ) { return DateHelper::formatDate($model->approved_on); },
		];
	}

	public function rules ()
	{
		return [
		];
	}

	/**
	 * Get all the comments tree for each language of a post.
	 *
	 * @param int $postId
	 *
	 * @return mixed
	 */
	public static function getCommentsForPost ( $postId )
	{
		$languages = LangEx::find()->all();
		$comments  = [];

		foreach ($languages as $language) {
			$comments[ $language->icu ] = self::find()->byPost($postId)->byLang($language->id)->firstComment()->all();
		}

		return $comments;
	}
}
