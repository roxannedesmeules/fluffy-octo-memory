<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category_lang`.
 * Has foreign keys to the tables:
 *
 * - `category`
 * - `lang`
 */
class m170826_174558_create_category_lang_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('category_lang', [
			'category_id' => $this->integer()->notNull(),
			'lang_id'     => $this->integer()->notNull(),
			'name'        => $this->string()->notNull(),
			'slug'        => $this->string()->notNull(),
		]);
		
		$this->addPrimaryKey('pk-category_lang', "category_lang", [ "category_id", "lang_id" ]);
		
		// creates index for column `category_id`
		$this->createIndex(
			'idx-category_lang-category_id',
			'category_lang',
			'category_id'
		);
		
		// add foreign key for table `category`
		$this->addForeignKey(
			'fk-category_lang-category_id',
			'category_lang',
			'category_id',
			'category',
			'id'
		);
		
		// creates index for column `lang_id`
		$this->createIndex(
			'idx-category_lang-lang_id',
			'category_lang',
			'lang_id'
		);
		
		// add foreign key for table `lang`
		$this->addForeignKey(
			'fk-category_lang-lang_id',
			'category_lang',
			'lang_id',
			'lang',
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
			'fk-category_lang-category_id',
			'category_lang'
		);
		
		// drops index for column `category_id`
		$this->dropIndex(
			'idx-category_lang-category_id',
			'category_lang'
		);
		
		// drops foreign key for table `lang`
		$this->dropForeignKey(
			'fk-category_lang-lang_id',
			'category_lang'
		);
		
		// drops index for column `lang_id`
		$this->dropIndex(
			'idx-category_lang-lang_id',
			'category_lang'
		);
		
		$this->dropTable('category_lang');
	}
}
