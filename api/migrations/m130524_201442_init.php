<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		$this->createTable('user', [
			'id'                   => $this->primaryKey(),
			'username'             => $this->string()->notNull()->unique(),
			'password_hash'        => $this->string()->notNull(),
			'password_reset_token' => $this->string(5)->unique(),
			"auth_token"           => $this->string(32)->notNull()->unique(),
			
			'is_active'  => $this->boolean()->notNull()->defaultValue(1),
			'created_on' => $this->dateTime()->notNull(),
			'updated_on' => $this->dateTime(),
			'last_login' => $this->dateTime(),
		], $tableOptions);
		
		$this->insert('user', [
			'id'            => 1,
			'username'      => 'mlleDesmeules',
			'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('AAAaaa111'),
			'auth_token'    => Yii::$app->getSecurity()->generateRandomString(32),
			'created_on'    => date('Y-m-d H:i:s'),
		]);
	}
	
	public function safeDown ()
	{
		$this->dropTable('user');
	}
}
