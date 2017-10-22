<?php

namespace app\models\tag;

use Yii;

/**
 * This is the model class for table "tag_lang".
 *
 * @property int     $tag_id
 * @property int     $lang_id
 * @property string  $name
 * @property string  $slug
 *
 * Relations :
 * @property Lang    $lang
 * @property TagBase $tag
 */
abstract class TagLangBase extends \yii\db\ActiveRecord
{
	/** @inheritdoc */
	public static function tableName () { return 'tag_lang'; }
	
	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "tag_id", "required" ],
			[ "tag_id", "integer" ],
			[
				[ 'tag_id' ], 'exist',
				'skipOnError'     => true,
				'targetClass'     => TagBase::className(),
				'targetAttribute' => [ 'tag_id' => 'id' ],
			],
			
			[ "lang_id", "required" ],
			[ "lang_id", "integer" ],
			[
				[ 'lang_id' ], 'exist',
				'skipOnError'     => true,
				'targetClass'     => Lang::className(),
				'targetAttribute' => [ 'lang_id' => 'id' ],
			],
			
			[ [ 'tag_id', 'lang_id' ], 'unique', 'targetAttribute' => [ 'tag_id', 'lang_id' ] ],
			
			[ "name", "string", "max" => 255 ],
			
			[ "slug", "string", "max" => 255 ],
			[ "slug", "unique" ],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'tag_id'  => Yii::t('app.tag', 'Tag ID'),
			'lang_id' => Yii::t('app.tag', 'Lang ID'),
			'name'    => Yii::t('app.tag', 'Name'),
			'slug'    => Yii::t('app.tag', 'Slug'),
		];
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getLang ()
	{
		return $this->hasOne(Lang::className(), [ 'id' => 'lang_id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getTag ()
	{
		return $this->hasOne(TagBase::className(), [ 'id' => 'tag_id' ]);
	}
	
	/**
	 * @inheritdoc
	 * @return TagLangQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new TagLangQuery(get_called_class());
	}
}
