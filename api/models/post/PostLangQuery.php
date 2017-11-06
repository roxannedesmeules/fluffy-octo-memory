<?php

namespace app\models\post;

/**
 * This is the ActiveQuery class for [[PostLang]].
 *
 * @see PostLangBase
 */
class PostLangQuery extends \yii\db\ActiveQuery
{
	/**
	 * @inheritdoc
	 * @return PostLangBase[]|array
	 */
	public function all ( $db = null ) { return parent::all($db); }

	/**
	 * @inheritdoc
	 * @return PostLangBase|array|null
	 */
	public function one ( $db = null ) { return parent::one($db); }

	public function byPost ( $postId )
	{
		return $this->andWhere([ "post_id" => $postId ]);
	}
}
