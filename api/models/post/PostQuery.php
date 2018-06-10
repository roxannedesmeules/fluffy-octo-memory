<?php

namespace app\models\post;

use app\models\tag\TagQuery;

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

	/**
	 * Add a condition to find a Post entry with a specific ID.
	 *
	 * @param int $postId
	 *
	 * @return $this
	 */
	public function id ( $postId )
	{
		return $this->andWhere([ Post::tableName() . ".id" => $postId]);
	}

	/**
	 * Add a condition to filter Post entries by category ID
	 *
	 * @param int $categoryId
	 *
	 * @return $this
	 */
	public function category ( $categoryId )
	{
		return $this->andWhere([ "category_id" => $categoryId ]);
	}

	/**
	 * Add a condition to filter Post entries by featured flag.
	 *
	 * @param int $flag
	 *
	 * @return $this
	 */
	public function featured ( $flag )
	{
		return $this->andWhere([ "is_featured"  => $flag ]);
	}

	/**
	 * Add a condition to filter Post entries by status ID that are set to published.
	 *
	 * @see PostStatus::PUBLISHED
	 *
	 * @return $this
	 */
	public function isPublished ()
	{
		return $this->status(PostStatus::PUBLISHED);
	}

	/**
	 * Add a condition to order Post entries by their published dates, from most recent to most ancient.
	 *
	 * @return $this
	 */
	public function orderPublication ()
	{
		return $this->orderBy([ "published_on" => SORT_DESC ]);
	}

	/**
	 * Add a condition to filter Post entries by their post status ID.
	 *
	 * @param int $statusId
	 *
	 * @return $this
	 */
	public function status ( $statusId )
	{
		return $this->andWhere([ "post_status_id" => $statusId ]);
	}

	/**
	 * Add a condition to filter Post entries that are linked to a specific Tag ID.
	 *
	 * @param int $tagId
	 *
	 * @return $this
	 */
	public function withTag ( $tagId )
	{
		return $this->innerJoinWith([
			"tags" => function ( TagQuery $query ) use ( $tagId ) {
				return $query->id($tagId);
			}
		]);
	}

	/**
	 * Add a condition to filter Post entries that have a translation in a specific
	 *
	 * @param int $langId
	 *
	 * @return $this
	 */
	public function withTranslationIn ( $langId )
	{
		$subQuery = PostLang::find()
							->select("post_id")
							->andWhere("post_id = " . Post::tableName() . ".id")
							->andWhere([ "lang_id" => $langId ]);

		return $this->andWhere([ "exists", $subQuery ]);
	}

	/**
	 * Add a condition to also fetch PostLang entries.
	 *
	 * @return $this
	 */
	public function withTranslations ()
	{
		return $this->with("postLangs");
	}
}
