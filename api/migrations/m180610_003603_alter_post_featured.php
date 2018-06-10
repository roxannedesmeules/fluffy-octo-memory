<?php

use yii\db\Migration;

/**
 * Class m180610_003603_alter_post_featured
 */
class m180610_003603_alter_post_featured extends Migration
{
	const TABLE  = "post";
	const COLUMN = "is_featured";

	/** @inheritdoc */
	public function safeUp ()
	{
		$this->addColumn(
			self::TABLE, self::COLUMN,
			$this->smallInteger(1)
			     ->defaultValue(0)
			     ->after("post_status_id")
		);
	}

	/** @inheritdoc */
	public function safeDown ()
	{
		$this->dropColumn(self::TABLE, self::COLUMN);
	}
}
