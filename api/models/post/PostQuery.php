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

	public function category ( $categoryId )
	{
		return $this->andWhere([ "category_id" => $categoryId ]);
	}

	public function isPublished ()
	{
		return $this->status(PostStatus::PUBLISHED);
	}

	public function orderPublication ()
	{
		return $this->orderBy([ "published_on" => SORT_DESC ]);
	}

	public function status ( $statusId )
	{
		return $this->andWhere([ "post_status_id" => $statusId ]);
	}

	public function withTranslationIn ( $lang )
	{
		$subQuery = PostLang::find()
							->select("post_id")
							->andWhere("post_id = " . Post::tableName() . ".id")
							->andWhere([ "lang_id" => $lang ]);

		return $this->andWhere([ "exists", $subQuery ]);
	}

	public function withTranslations ()
	{
		return $this->with("postLangs");
	}
}
