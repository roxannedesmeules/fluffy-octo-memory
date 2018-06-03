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
	 * @param $id
	 *
	 * @return $this
	 */
	public function byCategory ( $id )
	{
		return $this->andWhere([ "category_id" => $id ]);
	}
	
	/**
	 * @param $id
	 *
	 * @return $this
	 */
	public function byLang ( $id )
	{
		return $this->andWhere([ "lang_id" => $id ]);
	}

	public function bySlug ( $slug )
	{
		return $this->andWhere([ "slug" => $slug ]);
	}
}
