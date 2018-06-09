<?php

namespace app\models\category;

/**
 * This is the ActiveQuery class for [[CategoryLang]].
 *
 * @see CategoryLangBase
 */
class CategoryLangQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return CategoryLangBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }
	
	/**
	 * @inheritdoc
	 * @return CategoryLangBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }
	
	/**
	 * Add a condition to fetch CategoryLang entries matching a specific category ID.
	 *
	 * @param int $categoryId
	 *
	 * @return $this
	 */
	public function byCategory ( $categoryId )
	{
		return $this->andWhere([ "category_id" => $categoryId ]);
	}
	
	/**
	 * Add a condition to fetch CategoryLang entries matching a specific lang ID.
	 *
	 * @param int $langId
	 *
	 * @return $this
	 */
	public function byLang ( $langId )
	{
		return $this->andWhere([ "lang_id" => $langId ]);
	}

	/**
	 * Add a condition to fetch CategoryLang entries matching a specific slug.
	 *
	 * @param string $slug
	 *
	 * @return $this
	 */
	public function bySlug ( $slug )
	{
		return $this->andWhere([ "slug" => $slug ]);
	}
}
