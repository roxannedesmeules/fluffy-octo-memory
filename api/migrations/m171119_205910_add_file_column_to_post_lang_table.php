<?php

use yii\db\Migration;

/**
 * Handles adding file to table `post_lang`.
 * Has foreign keys to the tables:
 *
 * - `file`
 */
class m171119_205910_add_file_column_to_post_lang_table extends Migration
{
	/** @inheritdoc */
	public function up ()
	{
		$this->addColumn('post_lang', 'file_id', $this->integer()->defaultValue(null));
		$this->addColumn('post_lang', 'file_alt', $this->text()->defaultValue(null));

		// creates index for column `file_id`
		$this->createIndex(
			'idx-post_lang-file_id',
			'post_lang',
			'file_id'
		);

		// add foreign key for table `file`
		$this->addForeignKey(
			'fk-post_lang-file_id',
			'post_lang',
			'file_id',
			'file',
			'id',
			'CASCADE'
		);
	}

	/** @inheritdoc */
	public function down ()
	{
		// drops foreign key for table `file`
		$this->dropForeignKey(
			'fk-post_lang-file_id',
			'post_lang'
		);

		// drops index for column `file_id`
		$this->dropIndex(
			'idx-post_lang-file_id',
			'post_lang'
		);

		$this->dropColumn('post_lang', 'file_id');
		$this->dropColumn('post_lang', 'file_alt');
	}
}
