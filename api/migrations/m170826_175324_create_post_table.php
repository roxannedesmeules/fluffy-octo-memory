<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 * Has foreign keys to the tables:
 *
 * - `category`
 * - `post_status`
 */
class m170826_175324_create_post_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('post', [
			'id'             => $this->primaryKey(),
			'category_id'    => $this->integer()->notNull(),
			'post_status_id' => $this->integer()->notNull()->defaultValue(1),
			'created_on'     => $this->dateTime(),
			'updated_on'     => $this->dateTime(),
		]);
		
		// creates index for column `category_id`
		$this->createIndex(
			'idx-post-category_id',
			'post',
			'category_id'
		);
		
		// add foreign key for table `category`
		$this->addForeignKey(
			'fk-post-category_id',
			'post',
			'category_id',
			'category',
			'id'
		);
		
		// creates index for column `post_status_id`
		$this->createIndex(
			'idx-post-post_status_id',
			'post',
			'post_status_id'
		);
		
		// add foreign key for table `post_status`
		$this->addForeignKey(
			'fk-post-post_status_id',
			'post',
			'post_status_id',
			'post_status',
			'id'
		);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		// drops foreign key for table `category`
		$this->dropForeignKey(
			'fk-post-category_id',
			'post'
		);
		
		// drops index for column `category_id`
		$this->dropIndex(
			'idx-post-category_id',
			'post'
		);
		
		// drops foreign key for table `post_status`
		$this->dropForeignKey(
			'fk-post-post_status_id',
			'post'
		);
		
		// drops index for column `post_status_id`
		$this->dropIndex(
			'idx-post-post_status_id',
			'post'
		);
		
		$this->dropTable('post');
	}
}
