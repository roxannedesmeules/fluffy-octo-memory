<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lang`.
 */
class m170826_173544_create_lang_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('lang', [
			'id'     => $this->primaryKey(),
			'icu'    => $this->string(5)->notNull()->unique(),
			'name'   => $this->string()->notNull(),
			'native' => $this->string()->notNull(),
		]);
		
		$this->insert("lang", [ "icu" => "en-CA", "name" => "English (Canada)", "native" => "English (Canada)" ]);
		$this->insert("lang", [ "icu" => "fr-CA", "name" => "French (Canada)", "native" => "Français (Canada)" ]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		$this->dropTable('lang');
	}
}
