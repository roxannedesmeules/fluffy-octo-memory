<?php

namespace app\models\post;

/**
 * This is the ActiveQuery class for [[PostStatus]].
 *
 * @see PostStatusBase
 */
class PostStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PostStatusBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PostStatusBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
