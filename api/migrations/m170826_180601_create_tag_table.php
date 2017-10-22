<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag`.
 */
class m170826_180601_create_tag_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('tag', [
			'id'         => $this->primaryKey(),
			'created_on' => $this->dateTime(),
			'updated_on' => $this->dateTime(),
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		$this->dropTable('tag');
	}
}
