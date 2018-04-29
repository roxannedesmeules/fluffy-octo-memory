<?php

namespace app\models\user;

/**
 * This is the ActiveQuery class for [[UserProfileLang]].
 *
 * @see UserProfileLang
 */
class UserProfileLangQuery extends \yii\db\ActiveQuery
{
	/**
	 * {@inheritdoc}
	 * @return UserProfileLang[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * {@inheritdoc}
	 * @return UserProfileLang|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	/**
	 * @param $userId
	 *
	 * @return \app\models\user\UserProfileLangQuery
	 */
	public function byUser ( $userId )
	{
		return $this->andWhere([ "user_id" => $userId ]);
	}

	/**
	 * @param $langId
	 *
	 * @return \app\models\user\UserProfileLangQuery
	 */
	public function byLang ( $langId )
	{
		return $this->andWhere([ "lang_id" => $langId ]);
	}
}
