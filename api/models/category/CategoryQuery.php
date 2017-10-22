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
	
	public function id ( $categoryId )
	{
		return $this->andWhere([ "id" => $categoryId ]);
	}
	
	public function withTranslations ()
	{
		return $this->joinWith( "categoryLangs" );
	}
}
