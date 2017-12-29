<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m170826_172431_create_user_profile_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('user_profile', [
			'user_id'   => $this->primaryKey(),
			'firstname' => $this->string(),
			'lastname'  => $this->string(),
			'birthday'  => $this->dateTime(),
		]);
		
		// creates index for column `user_id`
		$this->createIndex(
			'idx-user_profile-user_id',
			'user_profile',
			'user_id'
		);
		
		// add foreign key for table `user`
		$this->addForeignKey(
			'fk-user_profile-user_id',
			'user_profile',
			'user_id',
			'user',
			'id'
		);
		
		$this->insert('user_profile', [
			'user_id'   => 1,
			'firstname' => 'Roxanne',
			'lastname'  => 'Desmeules',
			'birthday'  => date('Y-m-d', strtotime('5-7-1993')),
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown ()
	{
		// drops foreign key for table `user`
		$this->dropForeignKey(
			'fk-user_profile-user_id',
			'user_profile'
		);
		
		// drops index for column `user_id`
		$this->dropIndex(
			'idx-user_profile-user_id',
			'user_profile'
		);
		
		$this->dropTable('user_profile');
	}
}
