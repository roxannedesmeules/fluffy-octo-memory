<?php

namespace app\models\category;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;
use app\models\post\Post;
use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int                $id
 * @property int                $is_active
 * @property string             $created_on
 * @property string             $updated_on
 *
 * Relations
 * @property CategoryLang[] $categoryLangs
 * @property Lang[]         $langs
 * @property Post[]         $posts
 * @property integer        $postCount
 */
abstract class CategoryBase extends \yii\db\ActiveRecord
{
	
	const DATE_FORMAT = "Y-m-d H:i:s";
	
	const INACTIVE = 0;
	const ACTIVE   = 1;
	
	const ERROR   = 0;
	const SUCCESS = 1;
	
	const ERR_ON_SAVE       = "ERR_ON_SAVE";
	const ERR_ON_DELETE     = "ERR_ON_DELETE";
	const ERR_NOT_FOUND     = "ERR_NOT_FOUND";
	const ERR_DELETE_ACTIVE = "ERR_DELETE_ACTIVE";
	const ERR_DELETE_POSTS  = "ERR_DELETE_HAS_POSTS";

	const ERR_FIELD_REQUIRED    = "ERR_FIELD_REQUIRED";
	const ERR_FIELD_TYPE        = "ERR_FIELD_WRONG_TYPE";
	const ERR_FIELD_UNIQUE_LANG = "ERR_FIELD_UNIQUE_LANG";

	/** @var yii\db\Connection */
	public static $db;
	
	/** @inheritdoc */
	public function init ()
	{
		parent::init();
		
		self::$db = Yii::$app->db;
	}
	
	/** @inheritdoc */
	public static function tableName () { return 'category'; }
	
	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "is_active", "integer", "message" => self::ERR_FIELD_TYPE ],
			[ "is_active", "default", "value" => 0 ],

			[ "created_on", "safe" ],
			[ "updated_on", "safe" ],
		];
	}
	
	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'id'         => Yii::t('app.category', 'ID'),
			'is_active'  => Yii::t('app.category', 'Is Active'),
			'created_on' => Yii::t('app.category', 'Created On'),
			'updated_on' => Yii::t('app.category', 'Updated On'),
		];
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getCategoryLangs ()
	{
		return $this->hasMany(CategoryLang::className(), [ 'category_id' => 'id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getLangs ()
	{
		return $this->hasMany(Lang::className(), [ 'id' => 'lang_id' ])
		            ->viaTable('category_lang', [ 'category_id' => 'id' ]);
	}
	
	/** @return \yii\db\ActiveQuery */
	public function getPosts ()
	{
		return $this->hasMany(Post::className(), [ 'category_id' => 'id' ]);
	}

	/** @return int|string */
	public function getPostCount ()
	{
		return $this->hasMany(Post::className(), [ "category_id" => "id" ])->count();
	}
	
	/**
	 * @inheritdoc
	 * @return CategoryQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new CategoryQuery(get_called_class());
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
	 *
	 * @return bool
	 */
	public static function idExists ( $categoryId )
	{
		return self::find()->id($categoryId)->exists();
	}

	/**
	 * @param integer $categoryId
	 *
	 * @return bool
	 */
	public static function hasPosts ( $categoryId )
	{
		if (!self::idExists($categoryId)) {
			return false;
		}

		$category = self::find()->id($categoryId)->one();

		return !empty($category->posts);
	}

	/**
	 *
	 */
	public static function defineDbConnection () { self::$db = Yii::$app->db; }
}
