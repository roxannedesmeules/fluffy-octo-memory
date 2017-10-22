<?php

use yii\db\Migration;

/**
 * Handles the creation of table `asso_tag_post`.
 * Has foreign keys to the tables:
 *
 * - `tag`
 * - `post`
 */
class m170826_180933_create_asso_tag_post_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('asso_tag_post', [
			'tag_id'  => $this->integer()->notNull(),
			'post_id' => $this->integer()->notNull(),
		]);
		
		$this->addPrimaryKey("pk-asso_tag_post", "asso_tag_post", [ "tag_id", "post_id" ]);
		
		// creates index for column `tag_id`
		$this->createIndex(
			'idx-asso_tag_post-tag_id',
			'asso_tag_post',
			'tag_id'
		);
		
		// add foreign key for table `tag`
		$this->addForeignKey(
			'fk-asso_tag_post-tag_id',
			'asso_tag_post',
			'tag_id',
			'tag',
			'id',
			'CASCADE'
		);
		
		// creates index for column `post_id`
		$this->createIndex(
			'idx-asso_tag_post-post_id',
			'asso_tag_post',
			'post_id'
		);
		
		// add foreign key for table `post`
		$this->addForeignKey(
			'fk-asso_tag_post-post_id',
			'asso_tag_post',
			'post_id',
			'post',
			'id',
			'CASCADE'
		);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		// drops foreign key for table `tag`
		$this->dropForeignKey(
			'fk-asso_tag_post-tag_id',
			'asso_tag_post'
		);
		
		// drops index for column `tag_id`
		$this->dropIndex(
			'idx-asso_tag_post-tag_id',
			'asso_tag_post'
		);
		
		// drops foreign key for table `post`
		$this->dropForeignKey(
			'fk-asso_tag_post-post_id',
			'asso_tag_post'
		);
		
		// drops index for column `post_id`
		$this->dropIndex(
			'idx-asso_tag_post-post_id',
			'asso_tag_post'
		);
		
		$this->dropTable('asso_tag_post');
	}
}
