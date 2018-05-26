<?php

use yii\db\Migration;

/**
 * Class m180526_182559_alter_post_table
 */
class m180526_182559_alter_post_table extends Migration
{
	const TABLE = "post";

	/** @inheritdoc */
	public function safeUp ()
	{
		$this->addColumn(self::TABLE, "published_on", $this->dateTime()->after("updated_on"));
	}

	/** @inheritdoc */
	public function safeDown ()
	{
		$this->dropColumn(self::TABLE, "published_on");
	}
}
