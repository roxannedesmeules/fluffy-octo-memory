<?php

namespace app\models\app;

/**
 * This is the ActiveQuery class for [[ApiClient]].
 *
 * @see ApiClient
 */
class ApiClientQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return ApiClient[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }
	
	/**
	 * @inheritdoc
	 * @return ApiClient|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }
	
	public function key ( $key )
	{
		return $this->andWhere([ "key" => $key ]);
	}
	
	public function name ( $name )
	{
		return $this->andWhere([ "name" => $name ]);
	}
}
