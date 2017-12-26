<?php

namespace app\models\app;

use Yii;

/**
 * This is the model class for table "lang".
 *
 * @property int            $id
 * @property string         $icu
 * @property string         $name
 * @property string         $native
 */
class Lang extends \yii\db\ActiveRecord
{
	const EN = 1;
	const FR = 2;

	/** @inheritdoc */
	public static function tableName () { return 'lang'; }
	
	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "icu", "required" ],
			[ "icu", "string", "max" => 5 ],
			[ "icu", "unique" ]
			,
			[ "name", "required" ],
			[ "name", "string", "max" => 255 ],
			
			[ "native", "required" ],
			[ "native", "string", "max" => 255 ],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'id'     => Yii::t('app.lang', 'ID'),
			'icu'    => Yii::t('app.lang', 'Icu'),
			'name'   => Yii::t('app.lang', 'Name'),
			'native' => Yii::t('app.lang', 'Native'),
		];
	}
	
	/**
	 * @inheritdoc
	 * @return LangQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new LangQuery(get_called_class());
	}
	
	public static function idExists ( $id )
	{
		return self::find()->id($id)->exists();
	}
	
	public static function icuExists ( $icu )
	{
		return self::find()->icu($icu)->exists();
	}
}
