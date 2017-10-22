<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post_lang`.
 * Has foreign keys to the tables:
 *
 * - `post`
 * - `lang`
 * - `user`
 */
class m170826_180323_create_post_lang_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('post_lang', [
			'post_id'    => $this->integer()->notNull(),
			'lang_id'    => $this->integer()->notNull(),
			'user_id'    => $this->integer()->notNull(),
			'title'      => $this->string()->notNull(),
			'slug'       => $this->string()->unique(),
			'content'    => $this->text(),
			'created_on' => $this->dateTime(),
			'updated_on' => $this->dateTime(),
		]);
		
		$this->addPrimaryKey("pk-post_lang", "post_lang", [ "post_id", "lang_id" ]);
		
		// creates index for column `post_id`
		$this->createIndex(
			'idx-post_lang-post_id',
			'post_lang',
			'post_id'
		);
		
		// add foreign key for table `post`
		$this->addForeignKey(
			'fk-post_lang-post_id',
			'post_lang',
			'post_id',
			'post',
			'id',
			'CASCADE'
		);
		
		// creates index for column `lang_id`
		$this->createIndex(
			'idx-post_lang-lang_id',
			'post_lang',
			'lang_id'
		);
		
		// add foreign key for table `lang`
		$this->addForeignKey(
			'fk-post_lang-lang_id',
			'post_lang',
			'lang_id',
			'lang',
			'id',
			'CASCADE'
		);
		
		// creates index for column `user_id`
		$this->createIndex(
			'idx-post_lang-user_id',
			'post_lang',
			'user_id'
		);
		
		// add foreign key for table `user`
		$this->addForeignKey(
			'fk-post_lang-user_id',
			'post_lang',
			'user_id',
			'user',
			'id',
			'CASCADE'
		);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		// drops foreign key for table `post`
		$this->dropForeignKey(
			'fk-post_lang-post_id',
			'post_lang'
		);
		
		// drops index for column `post_id`
		$this->dropIndex(
			'idx-post_lang-post_id',
			'post_lang'
		);
		
		// drops foreign key for table `lang`
		$this->dropForeignKey(
			'fk-post_lang-lang_id',
			'post_lang'
		);
		
		// drops index for column `lang_id`
		$this->dropIndex(
			'idx-post_lang-lang_id',
			'post_lang'
		);
		
		// drops foreign key for table `user`
		$this->dropForeignKey(
			'fk-post_lang-user_id',
			'post_lang'
		);
		
		// drops index for column `user_id`
		$this->dropIndex(
			'idx-post_lang-user_id',
			'post_lang'
		);
		
		$this->dropTable('post_lang');
	}
}
