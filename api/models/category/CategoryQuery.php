<?php

namespace app\models\category;

/**
 * This is the ActiveQuery class for [[Category]].
 *
 * @see CategoryBase
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return CategoryBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }
	
	/**
	 * @inheritdoc
	 * @return CategoryBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	/**
	 * Add condition to get only active categories
	 *
	 * @return $this
	 */
	public function active ()
	{
		return $this->andWhere([ "is_active" => Category::ACTIVE ]);
	}

	/**
	 * Add condition to get category for specific ID
	 *
	 * @param integer $categoryId
	 *
	 * @return $this
	 */
	public function id ( $categoryId )
	{
		return $this->andWhere([ "id" => $categoryId ]);
	}

	/**
	 * Add condition to get only inactive categories
	 *
	 * @return $this
	 */
	public function inactive ()
	{
		return $this->andWhere([ "is_active" => Category::INACTIVE ]);
	}

	/**
	 * Join translations to the query
	 *
	 * @return $this
	 */
	public function withTranslations ()
	{
		return $this->joinWith( "categoryLangs" );
	}
}
