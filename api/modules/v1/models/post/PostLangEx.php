<?php

namespace app\modules\v1\models\post;

use app\models\post\PostLang;
use app\modules\v1\models\user\UserEx;

/**
 * Class PostLangEx
 *
 * @package app\modules\v1\models\post
 */
class PostLangEx extends PostLang
{
	/** @inheritdoc */
	public function getUser ()
	{
		return $this->hasOne(UserEx::className(), [ "id" => "user_id" ]);
	}

	/** @inheritdoc */
	public function getComments ()
	{
		return $this->hasMany(PostCommentEx::class, [ "post_id" => "post_id", "lang_id" => "lang_id" ]);
	}

	public function getCommentsTree ()
	{
		return PostCommentEx::getCommentsForPost($this->post_id);
	}

	public function getCommentsCount ()
	{
		return PostCommentEx::getCommentCountForPost($this->post_id);
	}
}
