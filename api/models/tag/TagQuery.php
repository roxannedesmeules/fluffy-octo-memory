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
		return $this->andWhere([ "id" => $tagId ]);
	}
}
