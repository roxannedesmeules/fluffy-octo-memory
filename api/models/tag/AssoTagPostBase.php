<?php

namespace app\models\tag;

/**
 * This is the model class for table "asso_tag_post".
 *
 * @property int  $tag_id
 * @property int  $post_id
 *
 * Relations :
 * @property Post $post
 * @property Tag  $tag
 */
class AssoTagPostBase extends \yii\db\ActiveRecord
{
	/** @inheritdoc */
	public static function tableName () { return 'asso_tag_post'; }

	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ [ 'tag_id', 'post_id' ], 'required' ],
			[ [ 'tag_id', 'post_id' ], 'integer' ],
			[ [ 'tag_id', 'post_id' ], 'unique', 'targetAttribute' => [ 'tag_id', 'post_id' ] ],
			[
				[ 'post_id' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Post::className(),
				'targetAttribute' => [ 'post_id' => 'id' ],
			],
			[
				[ 'tag_id' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Tag::className(),
				'targetAttribute' => [ 'tag_id' => 'id' ],
			],
		];
	}

	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'tag_id'  => 'Tag ID',
			'post_id' => 'Post ID',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost ()
	{
		return $this->hasOne(Post::className(), [ 'id' => 'post_id' ]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTag ()
	{
		return $this->hasOne(Tag::className(), [ 'id' => 'tag_id' ]);
	}
}
