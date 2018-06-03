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

	public function withSlug ( $slug )
	{
		return $this->joinWith([
			"categoryLangs" => function ( CategoryLangQuery $query ) use ( $slug ) {
				return $query->bySlug($slug);
			}
		]);
	}

	public function withTranslationIn ( $lang )
	{
		$subQuery = CategoryLang::find()
								->select("category_id")
								->where("category_id = " . Category::tableName() . ".id")
								->andWhere([ "lang_id" => $lang ]);

		return $this->andWhere([ "exists", $subQuery ]);
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
