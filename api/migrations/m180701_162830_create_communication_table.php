<?php

use yii\db\Migration;

/**
 * Handles the creation of table `communication`.
 */
class m180701_162830_create_communication_table extends Migration
{
	/** @inheritdoc */
	public function safeUp ()
	{
		$this->createTable('communication', [
			"id"         => $this->primaryKey(),
			"name"       => $this->string(255)->notNull(),
			"email"      => $this->string(255)->notNull(),
			"subject"    => $this->string(255)->defaultValue(null),
			"message"    => $this->text()->notNull(),
			"is_replied" => $this->smallInteger(1)->defaultValue(0),
			"created_on" => $this->dateTime(),
		]);

		$this->createIndex("idx-communication-email", "communication", "email");
	}

	/** @inheritdoc */
	public function safeDown ()
	{
		$this->dropTable('communication');
	}
}
