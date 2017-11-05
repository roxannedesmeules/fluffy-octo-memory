<?php
namespace app\modules\v1\admin\models\posts;

use app\components\validators\ArrayUniqueValidator;
use app\components\validators\TranslationValidator;
use app\helpers\DateHelper;
use app\models\category\Category;
use app\models\post\Post;
use app\models\post\PostStatus;

/**
 * Class PostEx
 *
 * @package app\modules\v1\admin\models\posts
 *
 * @SWG\Definition(
 *     definition = "PostList",
 *     type = "array",
 *     @SWG\Items( ref = "#/definitions/Post" )
 * )
 */
class PostEx extends Post
{
	public $translations;

	/** @inheritdoc */
	public function getPostLangs ()
	{
		return $this->hasMany(PostLangEx::className(), [ "post_id" => "id" ]);
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "Post",
	 *
	 *     @SWG\Property( property = "id", type = "integer" ),
	 *     @SWG\Property( property = "category_id", type = "integer" ),
	 *     @SWG\Property( property = "post_status_id", type = "integer" ),
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/PostTranslation" ) ),
	 *     @SWG\Property( property = "created_on", type = "string" ),
	 *     @SWG\Property( property = "updated_on", type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"id",
			"category_id",
			"post_status_id",
			"translations" => "postLangs",
			"created_on"   => function ( self $model ) { return DateHelper::formatDate($model->created_on, self::DATE_FORMAT); },
			"updated_on"   => function ( self $model ) { return DateHelper::formatDate($model->updated_on, self::DATE_FORMAT); },
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "PostForm",
	 *     required   = { "category_id", "post_status_id", "translations" },
	 *
	 *     @SWG\Property( property = "category_id", type = "integer" ),
	 *     @SWG\Property( property = "post_status_id", type = "integer" ),
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/PostTranslationForm" ) ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "category_id", "required" ],
			[ "category_id", "exist", "targetClass" => Category::className(), "targetAttribute" => [ "category_id" => "id" ] ],

			[ "post_status_id", "required" ],
			[ "post_status_id", "exist", "targetClass" => PostStatus::className(), "targetAttribute" => [ "post_status_id" => "id" ] ],

			[ "translations", "required" ],
			[ "translations", TranslationValidator::className(), "validator" => PostLangEx::className() ],
			[ "translations", ArrayUniqueValidator::className(), "uniqueKey" => "lang_id" ],
		];
	}
}
