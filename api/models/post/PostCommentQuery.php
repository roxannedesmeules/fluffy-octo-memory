<?php

namespace app\models\post;

/**
 * This is the ActiveQuery class for [[PostCommentBase]].
 *
 * @see PostCommentBase
 */
class PostCommentQuery extends \yii\db\ActiveQuery
{

	/**
	 * @inheritdoc
	 * @return PostCommentBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return PostCommentBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	/**
	 * Add condition to fetch post comment that are approved
	 *
	 * @return $this
	 */
	public function approved ()
	{
		return $this->andWhere([ "is_approved" => PostComment::IS_APPROVED ]);
	}

	/**
	 * Add condition to fetch post comment by comment ID
	 *
	 * @param int $commentId
	 *
	 * @return $this
	 */
	public function byId ( $commentId )
	{
		return $this->andWhere([ "id" => $commentId ]);
	}

	/**
	 * Add condition to fetch post comment by post ID
	 *
	 * @param int $postId
	 *
	 * @return $this
	 */
	public function byPost ( $postId )
	{
		return $this->andWhere([ "post_id" => $postId ]);
	}

	public function byLang ( $langId )
	{
		return $this->andWhere([ "lang_id" => $langId ]);
	}

	/**
	 * Add condition to fetch only post comment that aren't replies.
	 *
	 * @return $this
	 */
	public function firstComment ()
	{
		return $this->andWhere([ "reply_comment_id" => null ]);
	}
}
