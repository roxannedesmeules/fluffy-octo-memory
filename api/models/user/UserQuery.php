<?php

namespace app\models\user;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return UserBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }
	
	/**
	 * @inheritdoc
	 * @return UserBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }
	
	public function authToken ( $token )
	{
		return $this->andWhere([ "auth_token" => $token ]);
	}
	
	public function id ( $id ) 
	{
		return $this->andWhere([ "id" => $id ]);
	}
	
	public function username ( $username )
	{
		return $this->andWhere([ "username" => $username ]);
	}
}
