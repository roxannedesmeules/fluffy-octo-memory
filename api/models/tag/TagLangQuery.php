<?php

namespace app\models\tag;

/**
 * This is the ActiveQuery class for [[TagLang]].
 *
 * @see TagLangBase
 */
class TagLangQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return TagLangBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return TagLangBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	/**
	 * Add a condition to fetch TagLang entries matching a specific lang ID.
	 *
	 * @param integer $langId
	 *
	 * @return $this
	 */
	public function byLang ( $langId )
	{
		return $this->andWhere([ "lang_id" => $langId ]);
	}

	/**
	 * Add a condition to fetch TagLang entries matching a specific slug.
	 *
	 * @param $slug
	 *
	 * @return $this
	 */
	public function bySlug ( $slug )
	{
		return $this->andWhere([ "slug" => $slug ]);
	}

	/**
	 * Add a condition to fetch TagLang entries matching a specific tag ID.
	 *
	 * @param integer $tagId
	 *
	 * @return $this
	 */
	public function byTag ( $tagId )
	{
		return $this->andWhere([ "tag_id" => $tagId ]);
	}
}
