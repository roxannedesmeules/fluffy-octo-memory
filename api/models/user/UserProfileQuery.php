<?php

namespace app\models\user;

/**
 * This is the ActiveQuery class for [[UserProfile]].
 *
 * @see UserProfileBase
 */
class UserProfileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserProfileBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserProfileBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
