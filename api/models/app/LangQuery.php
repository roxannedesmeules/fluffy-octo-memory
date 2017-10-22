<?php

namespace app\models\app;

/**
 * This is the ActiveQuery class for [[Lang]].
 *
 * @see Lang
 */
class LangQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return Lang[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }
	
	/**
	 * @inheritdoc
	 * @return Lang|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }
	
	
	public function id ( $id ) { return $this->andWhere([ "id" => $id ]); }
	
	public function icu ( $icu ) { return $this->andWhere([ "icu" => $icu ]); }
}
