<?php

use yii\db\Migration;

/**
 * Class m180526_182607_alter_post_lang_table
 */
class m180526_182607_alter_post_lang_table extends Migration
{
	const TABLE = "post_lang";

	/** @inheritdoc */
	public function safeUp ()
	{
		$this->addColumn(self::TABLE, "summary", $this->string(180)->after("slug"));
	}

	/** @inheritdoc */
	public function safeDown ()
	{
		$this->dropColumn(self::TABLE, "summary");
	}
}
