<?php

namespace app\models\post;

/**
 * This is the ActiveQuery class for [[PostLang]].
 *
 * @see PostLangBase
 */
class PostLangQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PostLangBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PostLangBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
