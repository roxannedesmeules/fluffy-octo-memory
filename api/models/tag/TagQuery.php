<?php

namespace app\models\tag;

/**
 * This is the ActiveQuery class for [[Tag]].
 *
 * @see TagBase
 */
class TagQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TagBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TagBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
