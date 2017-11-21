<?php
namespace app\models\app;

/**
 * Class FileQuery
 *
 * @package app\models\app
 */
class FileQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return File[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return File[]|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }
}
