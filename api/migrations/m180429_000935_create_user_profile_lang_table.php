<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile_lang`.
 */
class m180429_000935_create_user_profile_lang_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp ()
	{
		$this->createTable('user_profile_lang', [
			'user_id'   => $this->integer(11)->notNull(),
			'lang_id'   => $this->integer(11)->notNull(),
			'biography' => $this->text()->defaultValue(null),
			'job_title' => $this->string(255)->defaultValue(null),
			"PRIMARY KEY(user_id,lang_id)",
		]);

		$this->createIndex(
			'idx-user_profile_lang-user_id',
			'user_profile_lang',
			'user_id'
		);

		$this->addForeignKey(
			'fk-user_profile_lang-user_id',
			'user_profile_lang',
			'user_id',
			'user',
			'id',
			'CASCADE'
		);

		$this->createIndex(
			'idx-user_profile_lang-lang_id',
			'user_profile_lang',
			'lang_id'
		);

		$this->addForeignKey(
			'fk-user_profile_lang-lang_id',
			'user_profile_lang',
			'lang_id',
			'lang',
			'id',
			'CASCADE'
		);

		$this->insert("user_profile_lang", [
			"user_id" => 1,
			"lang_id" => 1,
			"biography" => "",
			"job_title" => "Web application developer",
		]);

		$this->insert("user_profile_lang", [
			"user_id" => 1,
			"lang_id" => 2,
			"biography" => "",
			"job_title" => "Développeur d'application web",
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown ()
	{
		// drops foreign key for table `user`
		$this->dropForeignKey(
			'fk-user_profile_lang-user_id',
			'user_profile_lang'
		);

		// drops index for column `user_id`
		$this->dropIndex(
			'idx-user_profile_lang-user_id',
			'user_profile_lang'
		);

		// drops foreign key for table `lang`
		$this->dropForeignKey(
			'fk-user_profile_lang-lang_id',
			'user_profile_lang'
		);

		// drops index for column `lang_id`
		$this->dropIndex(
			'idx-user_profile_lang-lang_id',
			'user_profile_lang'
		);

		$this->dropTable('user_profile_lang');
	}
}
