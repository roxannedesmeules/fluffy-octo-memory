<?php

namespace app\models\post;

/**
 * This is the ActiveQuery class for [[Post]].
 *
 * @see Post
 */
class PostQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return Post[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return Post|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	public function id ( $postId )
	{
		return $this->andWhere([ "id" => $postId]);
	}

	public function status ( $statusId )
	{
		return $this->andWhere([ "post_status_id" => $statusId ]);
	}

	public function withTranslations ()
	{
		return $this->with("postLangs");
	}
}
