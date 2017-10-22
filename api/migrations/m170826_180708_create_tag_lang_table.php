<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag_lang`.
 * Has foreign keys to the tables:
 *
 * - `tag`
 * - `lang`
 */
class m170826_180708_create_tag_lang_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('tag_lang', [
			'tag_id'  => $this->integer()->notNull(),
			'lang_id' => $this->integer()->notNull(),
			'name'    => $this->string(),
			'slug'    => $this->string()->unique(),
		]);
		
		$this->addPrimaryKey("pk-tag_lang", "tag_lang", [ "tag_id", "lang_id" ]);
		
		// creates index for column `tag_id`
		$this->createIndex(
			'idx-tag_lang-tag_id',
			'tag_lang',
			'tag_id'
		);
		
		// add foreign key for table `tag`
		$this->addForeignKey(
			'fk-tag_lang-tag_id',
			'tag_lang',
			'tag_id',
			'tag',
			'id',
			'CASCADE'
		);
		
		// creates index for column `lang_id`
		$this->createIndex(
			'idx-tag_lang-lang_id',
			'tag_lang',
			'lang_id'
		);
		
		// add foreign key for table `lang`
		$this->addForeignKey(
			'fk-tag_lang-lang_id',
			'tag_lang',
			'lang_id',
			'lang',
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
			'fk-tag_lang-tag_id',
			'tag_lang'
		);
		
		// drops index for column `tag_id`
		$this->dropIndex(
			'idx-tag_lang-tag_id',
			'tag_lang'
		);
		
		// drops foreign key for table `lang`
		$this->dropForeignKey(
			'fk-tag_lang-lang_id',
			'tag_lang'
		);
		
		// drops index for column `lang_id`
		$this->dropIndex(
			'idx-tag_lang-lang_id',
			'tag_lang'
		);
		
		$this->dropTable('tag_lang');
	}
}
