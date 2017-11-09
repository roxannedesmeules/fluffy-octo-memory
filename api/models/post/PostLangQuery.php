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

	/**
	 * Condition where entry match specific post
	 *
	 * @param integer $postId
	 *
	 * @return $this
	 */
	public function byPost ( $postId )
	{
		return $this->andWhere([ "post_id" => $postId ]);
	}

	/**
	 * Condition where entry match specific language
	 *
	 * @param integer $langId
	 *
	 * @return $this
	 */
	public function byLang ( $langId )
	{
		return $this->andWhere([ "lang_id" => $langId ]);
	}
}
