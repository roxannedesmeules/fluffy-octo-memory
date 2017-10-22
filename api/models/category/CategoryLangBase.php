<?php
namespace app\models\category;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;
use Yii;

/**
 * This is the model class for table "category_lang".
 *
 * @property int          $category_id
 * @property int          $lang_id
 * @property string       $name
 * @property string       $slug
 *
 * Relations :
 * @property Category $category
 * @property Lang     $lang
 */
class CategoryLangBase extends \yii\db\ActiveRecord
{
	
	const ERROR   = 0;
	const SUCCESS = 1;
	
	const ERR_ON_SAVE            = "ERR_ON_SAVE";
	const ERR_ON_DELETE          = "ERR_ON_DELETE";
	const ERR_NOT_FOUND          = "ERR_NOT_FOUND";
	const ERR_CATEGORY_NOT_FOUND = "ERR_CATEGORY_NOT_FOUND";
	const ERR_LANG_NOT_FOUND     = "ERR_LANG_NOT_FOUND";
	
	/** @inheritdoc */
	public static function tableName () { return 'category_lang'; }
	
	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "category_id", "required" ],
			[ "category_id", "integer" ],
			[
				[ 'category_id' ], 'exist',
				'skipOnError'     => true,
				'targetClass'     => Category::className(),
				'targetAttribute' => [ 'category_id' => 'id' ],
			],
			
			[ "lang_id", "required" ],
			[ "lang_id", "integer" ],
			[
				[ 'lang_id' ], 'exist',
				'skipOnError'     => true,
				'targetClass'     => Lang::className(),
				'targetAttribute' => [ 'lang_id' => 'id' ],
			],
			
			[ [ 'category_id', 'lang_id' ], 'unique', 'targetAttribute' => [ 'category_id', 'lang_id' ] ],
			
			[ "name", "required" ],
			[ "name", "string", "max" => 255 ],
			[ "name", "unique" ],
			
			[ "slug", "required" ],
			[ "slug", "string", "max" => 255 ],
			[ "slug", "unique" ],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'category_id' => Yii::t('app.category', 'Category ID'),
			'lang_id'     => Yii::t('app.category', 'Lang ID'),
			'name'        => Yii::t('app.category', 'Name'),
			'slug'        => Yii::t('app.category', 'Slug'),
		];
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getCategory ()
	{
		return $this->hasOne(Category::className(), [ 'id' => 'category_id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getLang ()
	{
		return $this->hasOne(Lang::className(), [ 'id' => 'lang_id' ]);
	}
	
	/**
	 * @inheritdoc
	 * @return CategoryLangQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new CategoryLangQuery(get_called_class());
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
	
	/**
	 * @param int $categoryId
	 * @param int $langId
	 *
	 * @return bool
	 */
	public static function translationExists ( $categoryId, $langId )
	{
		return self::find()->byCategory($categoryId)->byLang($langId)->exists();
	}
}
