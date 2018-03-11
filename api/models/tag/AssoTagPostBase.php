<?php

namespace app\models\tag;

use app\helpers\ArrayHelperEx;
use app\models\post\Post;

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
	const ERROR   = 0;
	const SUCCESS = 1;

	const ERR_ON_DELETE = "ERR_ON_DELETE";

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

	/**
	 * Build an array to use when returning from another method. The status will automatically
	 * set to ERROR, then $error passed in param will be associated to the error key.
	 *
	 * @param $error
	 *
	 * @return array
	 */
	public static function buildError ( $error )
	{
		return [ "status" => self::ERROR, "error" => $error ];
	}

	/**
	 * Build an array to use when returning from another method. The status will be automatically
	 * set to SUCCESS, then the $params will be merged with the array and be returned.
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public static function buildSuccess ( $params )
	{
		return ArrayHelperEx::merge([ "status" => self::SUCCESS ], $params);
	}
}
