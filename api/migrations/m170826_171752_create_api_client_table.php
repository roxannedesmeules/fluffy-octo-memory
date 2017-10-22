<?php

use yii\db\Migration;

/**
 * Handles the creation of table `api_client`.
 */
class m170826_171752_create_api_client_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable("api_client", [
			'id'          => $this->primaryKey(),
			'name'        => $this->string()->notNull()->unique(),
			'description' => $this->text()->notNull(),
			'key'         => $this->string(32)->notNull()->unique(),
		]);
		
		$this->insert('api_client', [
			'id'          => 1,
			'name'        => 'Admin',
			'description' => 'Admin panel api key',
			'key'         => Yii::$app->getSecurity()->generateRandomString(32),
		]);
		
		$this->insert('api_client', [
			'id'          => 2,
			'name'        => 'Blog',
			'description' => 'Blog api key',
			'key'         => Yii::$app->getSecurity()->generateRandomString(32),
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		$this->dropTable("api_client");
	}
}
