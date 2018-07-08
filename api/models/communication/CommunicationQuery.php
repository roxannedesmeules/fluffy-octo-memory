<?php

namespace app\models\communication;

/**
 * This is the ActiveQuery class for [[Communication]].
 *
 * @see Communication
 */
class CommunicationQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return Communication[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return Communication|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	/**
	 * Add a condition to find a communication entry for a specific ID.
	 *
	 * @param int $id
	 *
	 * @return self
	 */
	public function byId ( $id )
	{
		return $this->andWhere([ "id" => $id ]);
	}

	/**
	 * Add a condition to filter the communication entries by their date of creation to always display the most recent
	 * as the first one.
	 *
	 * @return self
	 */
	public function mostRecent ()
	{
		return $this->orderBy([ "created_on" => SORT_DESC ]);
	}
}
