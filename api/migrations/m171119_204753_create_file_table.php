<?php

use yii\db\Migration;

/**
 * Handles the creation of table `file`.
 */
class m171119_204753_create_file_table extends Migration
{
	/** @inheritdoc */
	public function up ()
	{
		$this->createTable('file',
		                   [
			                   'id'         => $this->primaryKey(),
			                   'name'       => $this->string(255)->notNull(),
			                   'path'       => $this->string(255)->notNull()->unique(),
			                   'created_on' => $this->dateTime(),
			                   'is_deleted' => $this->integer(1)->defaultValue(0),
		                   ]);
	}

	/** @inheritdoc */
	public function down ()
	{
		$this->dropTable('file');
	}
}
