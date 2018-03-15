<?php

namespace app\models\tag;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;
use app\models\post\Post;
use app\models\post\PostStatus;
use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int       $id
 * @property string    $created_on
 * @property string    $updated_on
 *
 * Relations :
 * @property Post[]    $posts
 * @property Post[]    $publishedPosts
 * @property TagLang[] $tagLangs
 * @property Lang[]    $langs
 */
abstract class TagBase extends \yii\db\ActiveRecord
{
	const DATE_FORMAT = 'Y-m-d H:i:s';

	const ERROR   = 0;
	const SUCCESS = 1;

	const ERR_ON_SAVE           = "ERR_ON_SAVE";
	const ERR_ON_DELETE         = "ERR_ON_DELETE";
	const ERR_NOT_FOUND         = "ERR_NOT_FOUND";
	const ERR_DELETE_POSTS      = "ERR_DELETE_LINKED_TO_PUBLISHED_POST";
	const ERR_HAS_TRANSLATIONS  = "ERR_HAS_TRANSLATIONS";
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
	public function getPosts ()
	{
		return $this->hasMany(Post::className(), [ 'id' => 'post_id' ])
		            ->viaTable('asso_tag_post', [ 'tag_id' => 'id' ]);
	}

	/** @return \yii\db\ActiveQuery */
	public function getPublishedPosts ()
	{
		return $this->hasMany(Post::className(), [ 'id' => 'post_id' ])
		            ->where("post_status_id = :published", [ ":published" => PostStatus::PUBLISHED ])
		            ->viaTable('asso_tag_post', [ 'tag_id' => 'id' ]);
	}

	/** @return \yii\db\ActiveQuery */
	public function getTagLangs ()
	{
		return $this->hasMany(TagLang::className(), [ 'tag_id' => 'id' ]);
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
	 *
	 */
	public static function defineDbConnection () { self::$db = Yii::$app->db; }

	/**
	 * Verify if a tag with the passed ID exists
	 *
	 * @param integer $tagId
	 *
	 * @return mixed
	 */
	public static function idExists ( $tagId )
	{
		return self::find()->id($tagId)->exists();
	}

	/**
	 * @param $tagId
	 *
	 * @return bool
	 */
	public static function hasPublishedPosts ( $tagId )
	{
		if (!self::idExists($tagId)) {
			return false;
		}

		$model = self::find()->id($tagId)->one();

		return !empty($model->publishedPosts);
	}

	/**
	 * Verify if the Tag has translations
	 *
	 * @return bool
	 */
	public function hasTranslations ()
	{
		return !empty($this->tagLangs);
	}
}
