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
					return $model->user->userProfile->getFullname();
				} else {
					return $model->author;
				}
			},
			"user" => function ( self $model ) {
				if (!is_null($model->user_id)) {
					return [ "id" => $model->user_id, "fullname" => $model->user->userProfile->getFullname() ];
				} else {
					return null;
				}
			},
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
			[ "post_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[
				[ "post_id" ], "exist",
				"skipOnError"     => true,
				"targetClass"     => PostEx::className(),
				"targetAttribute" => [ "post_id" => "id" ],
				"message"         => self::ERR_FIELD_NOT_FOUND,
			],

			[ "lang_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[
				[ "lang_id" ], "exist",
				"skipOnError"     => true,
				"targetClass"     => LangEx::className(),
				"targetAttribute" => [ "lang_id" => "id" ],
				"message"         => self::ERR_FIELD_NOT_FOUND,
			],

			[
				"reply_comment_id", "exist",
				"skipOnEmpty"     => true,
				"targetClass"     => self::className(),
				"targetAttribute" => [ "reply_comment_id" => "id" ],
				"message"         => self::ERR_FIELD_NOT_FOUND,
			],

			[ "comment", "required", "message" => self::ERR_FIELD_REQUIRED ],
		];
	}

	/**
	 * This method will create a single comment where the author is the authenticated user. Since it is created from the
	 * admin panel, the comment is automatically approved.
	 *
	 * @param int  $postId
	 * @param self $data
	 *
	 * @return array
	 */
	public static function createByUser ( $postId, $data )
	{
		//  update some of the data, so the comment is properly created
		$data->user_id     = \Yii::$app->getUser()->id;
		$data->is_approved = self::IS_APPROVED;

		//  call the general create method to create the comment.
		return self::createOne($postId, $data->lang_id, $data);
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
