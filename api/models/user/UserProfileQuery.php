<?php

namespace app\models\user;

/**
 * This is the ActiveQuery class for [[UserProfile]].
 *
 * @see UserProfileBase
 */
class UserProfileQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return UserProfileBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return UserProfileBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	/**
	 * Add condition where the profile user id must match the one passed in parameter.
	 *
	 * @param integer $userId
	 *
	 * @return $this
	 */
	public function user ( $userId )
	{
		return $this->andWhere([ "user_id" => $userId ]);
	}
}
