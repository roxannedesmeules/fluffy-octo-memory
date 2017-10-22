<?php

namespace app\models\tag;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int           $id
 * @property string        $created_on
 * @property string        $updated_on
 *
 * Relations :
 * @property AssoTagPost[] $assoTagPosts
 * @property Post[]        $posts
 * @property TagLangBase[] $tagLangs
 * @property Lang[]        $langs
 */
abstract class TagBase extends \yii\db\ActiveRecord
{
	
	const DATE_FORMAT = 'Y-m-d H:i:s';
	
	/** @inheritdoc */
	public static function tableName () { return 'tag'; }
	
	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "created_on", "safe" ],
			[ "updated_on", "safe" ],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'id'         => Yii::t('app.tag', 'ID'),
			'created_on' => Yii::t('app.tag', 'Created On'),
			'updated_on' => Yii::t('app.tag', 'Updated On'),
		];
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getAssoTagPosts ()
	{
		return $this->hasMany(AssoTagPost::className(), [ 'tag_id' => 'id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getPosts ()
	{
		return $this->hasMany(Post::className(), [ 'id' => 'post_id' ])
		            ->viaTable('asso_tag_post', [ 'tag_id' => 'id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getTagLangs ()
	{
		return $this->hasMany(TagLangBase::className(), [ 'tag_id' => 'id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getLangs ()
	{
		return $this->hasMany(Lang::className(), [ 'id' => 'lang_id' ])
		            ->viaTable('tag_lang', [ 'tag_id' => 'id' ]);
	}
	
	/**
	 * @inheritdoc
	 * @return TagQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new TagQuery(get_called_class());
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
