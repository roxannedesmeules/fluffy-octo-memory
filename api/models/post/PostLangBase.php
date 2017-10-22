<?php

namespace app\models\post;

use Yii;

/**
 * This is the model class for table "post_lang".
 *
 * @property int    $post_id
 * @property int    $lang_id
 * @property int    $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $created_on
 * @property string $updated_on
 *
 * Relations :
 * @property Lang   $lang
 * @property Post   $post
 * @property User   $user
 */
abstract class PostLangBase extends \yii\db\ActiveRecord
{
	
	const DATE_FORMAT = 'Y-m-d H:i:s';
	
	/** @inheritdoc */
	public static function tableName () { return 'post_lang'; }
	
	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "post_id", "required" ],
			[ "post_id", "integer" ],
			[
				[ 'post_id' ], 'exist',
				'skipOnError'     => true,
				'targetClass'     => Post::className(),
				'targetAttribute' => [ 'post_id' => 'id' ],
			],
			
			[ "lang_id", "required" ],
			[ "lang_id", "integer" ],
			[
				[ 'lang_id' ], 'exist',
				'skipOnError'     => true,
				'targetClass'     => Lang::className(),
				'targetAttribute' => [ 'lang_id' => 'id' ],
			],
			
			[ [ 'post_id', 'lang_id' ], 'unique', 'targetAttribute' => [ 'post_id', 'lang_id' ] ],
			
			[ "user_id", "required" ],
			[ "user_id", "integer" ],
			[
				[ 'user_id' ], 'exist',
				'skipOnError'     => true,
				'targetClass'     => User::className(),
				'targetAttribute' => [ 'user_id' => 'id' ],
			],
			
			[ "title", "required" ],
			[ "title", "string", "max" => 255 ],
			
			[ "slug", "string", "max" => 255 ],
			[ "slug", "unique" ],
			
			[ "content", "string" ],
			
			[ "created_on", "safe" ],
			[ "updated_on", "safe" ],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'post_id'    => Yii::t('app.post', 'Post ID'),
			'lang_id'    => Yii::t('app.post', 'Lang ID'),
			'user_id'    => Yii::t('app.post', 'User ID'),
			'title'      => Yii::t('app.post', 'Title'),
			'slug'       => Yii::t('app.post', 'Slug'),
			'content'    => Yii::t('app.post', 'Content'),
			'created_on' => Yii::t('app.post', 'Created On'),
			'updated_on' => Yii::t('app.post', 'Updated On'),
		];
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getLang ()
	{
		return $this->hasOne(Lang::className(), [ 'id' => 'lang_id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getPost ()
	{
		return $this->hasOne(Post::className(), [ 'id' => 'post_id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getUser ()
	{
		return $this->hasOne(User::className(), [ 'id' => 'user_id' ]);
	}
	
	/**
	 * @inheritdoc
	 * @return PostLangQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new PostLangQuery(get_called_class());
	}
	
	/** @inheritdoc */
	public function beforeSave ( $insert )
	{
		if (!parent::beforeSave($insert)) {
			return false;
		}
		
		switch ($insert) {
			case true:
				$this->created_on = date(self::DATE_FORMAT);
				break;
			
			case false:
				$this->updated_on = date(self::DATE_FORMAT);
				break;
		}
		
		return true;
	}
}
