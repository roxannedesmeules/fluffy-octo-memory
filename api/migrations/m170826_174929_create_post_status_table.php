<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post_status`.
 */
class m170826_174929_create_post_status_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('post_status', [
			'id'   => $this->primaryKey(),
			'name' => $this->string()->notNull()->unique(),
		]);
		
		$this->insert('post_status', [ "id" => 1, "name" => "draft" ]);
		$this->insert('post_status', [ "id" => 2, "name" => "unpublished" ]);
		$this->insert('post_status', [ "id" => 3, "name" => "published" ]);
		$this->insert('post_status', [ "id" => 4, "name" => "archived" ]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		$this->dropTable('post_status');
	}
}
