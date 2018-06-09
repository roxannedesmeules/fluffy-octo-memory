<?php

namespace app\models\tag;

/**
 * This is the ActiveQuery class for [[Tag]].
 *
 * @see TagBase
 */
class TagQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return TagBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return TagBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	/**
	 * Add condition to find a tag with a specific ID
	 *
	 * @param integer $tagId
	 *
	 * @return $this
	 */
	public function id ( $tagId )
	{
		return $this->andWhere([ Tag::tableName() . ".id" => $tagId ]);
	}

	/**
	 * Add a condition to find Tags that are linked to at least one published post.
	 *
	 * @return $this
	 */
	public function withPublishedPosts ()
	{
		return $this->joinWith("publishedPosts");
	}

	/**
	 * Add a condition to fetch Tag entries having a translation with a specific slug.
	 *
	 * @param string $slug
	 *
	 * @return $this
	 */
	public function withSlug ( $slug )
	{
		return $this->joinWith([
			"tagLangs" => function ( TagLangQuery $query ) use ( $slug ) {
				return $query->bySlug($slug);
			}
		]);
	}

	/**
	 * Add a condition to fetch Tags with all their translations.
	 *
	 * @return $this
	 */
	public function withTranslations ()
	{
		return $this->joinWith([ "tagLangs" ]);
	}

	/**
	 * Add a condition to fetch Tags entries with a translation in a specific language.
	 *
	 * @param int $langId
	 *
	 * @return $this
	 */
	public function withTranslationIn ( $langId )
	{
		$subQuery = TagLang::find()
		                    ->select("tag_id")
		                    ->andWhere("tag_id = " . Tag::tableName() . ".id")
		                    ->andWhere([ "lang_id" => $langId ]);

		return $this->andWhere([ "exists", $subQuery ]);
	}
}
