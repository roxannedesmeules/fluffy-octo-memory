<?php
namespace app\modules\v1\admin\models\post;

use app\components\validators\ArrayUniqueValidator;
use app\components\validators\TranslationValidator;
use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;
use app\models\post\Post;
use app\modules\v1\admin\models\category\CategoryEx;

/**
 * Class PostEx
 *
 * @package app\modules\v1\admin\models\post
 *
 * @SWG\Definition(
 *     definition = "PostList",
 *     type = "array",
 *     @SWG\Items( ref = "#/definitions/Post" )
 * )
 *
 * @property array $translations
 */
class PostEx extends Post
{
	/** @var array  */
	public $translations = [];

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
	 *     required   = { "category_id", "translations" },
	 *
	 *     @SWG\Property( property = "category_id", type = "integer" ),
	 *     @SWG\Property( property = "post_status_id", type = "integer" ),
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/PostTranslationForm" ) ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "category_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "category_id", "integer",  "message" => self::ERR_FIELD_TYPE ],
			[
				"category_id", "exist",
				"targetClass"     => CategoryEx::className(),
				"targetAttribute" => [ "category_id" => "id" ],
				"message"         => self::ERR_FIELD_NOT_FOUND,
			],

			[ "post_status_id", "integer", "message" => self::ERR_FIELD_TYPE ],
			[
				"post_status_id", "exist",
				"targetClass"     => PostStatusEx::className(),
				"targetAttribute" => [ "post_status_id" => "id" ],
				"message"         => self::ERR_FIELD_NOT_FOUND,
			],

			[ "translations", "required", "strict" => true, "message" => self::ERR_FIELD_REQUIRED ],
			[ "translations", TranslationValidator::className(), "validator" => PostLangEx::className() ],
			[ "translations", ArrayUniqueValidator::className(), "uniqueKey" => "lang_id", "message" => self::ERR_FIELD_UNIQUE_LANG ],
		];
	}

	/**
	 * This method will create a Post entry, then all translations.
	 *
	 * @param self               $post
	 * @param PostLangEx[]|array $translations
	 *
	 * @return array
	 */
	public static function createWithTranslations ( $post, $translations )
	{
		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		//  create the post entry
		$result = self::createPost($post);

		//  in case of error, rollback and return error
		if ($result[ "status" ] === self::ERROR) {
			$transaction->rollBack();

			return $result;
		}

		//  keep the post ID
		$postId = $result[ "post_id" ];

		//  create all translations
		$result = PostLangEx::manageTranslations($postId, $translations);

		//  if one of the translations status is an error, then return all errors
		if (in_array(PostLangEx::ERROR, ArrayHelperEx::getColumn($result, "status"))) {
			$transaction->rollBack();

			return self::buildError([
				"translations" => ArrayHelperEx::filterInArrayAtIndex(PostLangEx::ERROR, $result, "status")
			]);
		}

		//  commit all changes made to DB
		$transaction->commit();

		//  return post ID
		return self::buildSuccess([ "post_id" => $postId ]);
	}

	/**
	 * This method will delete a Post entry with all associated translations
	 *
	 * @param integer $postId
	 *
	 * @return array
	 */
	public static function deleteWithTranslations ( $postId )
	{
		//  set the $db property
		self::defineDbConnection();

		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		//  delete translations first to avoid foreign key dependencies
		$result = PostLangEx::deleteTranslations($postId);

		//  in case of error, rollback and return error
		if ($result[ "status" ] === PostLangEx::ERROR) {
			$transaction->rollBack();

			return self::buildError([ "translations" => $result[ "error" ] ]);
		}

		//  delete the post itself
		$result = self::deletePost($postId);

		//  in case of error, rollback and return error
		if ($result[ "status" ] === self::ERROR) {
			$transaction->rollBack();

			return $result;
		}

		//  commit translations
		$transaction->commit();

		return self::buildSuccess([]);
	}

	/**
	 * Get a list of all post with their translations
	 *
	 * @param integer $status
	 *
	 * @return PostEx[]|array
	 */
	public static function getAllWithTranslations ( $status = -1 )
	{
		$query = self::find()->withTranslations();

		if ( PostStatusEx::idExists($status) ) {
			$query->status($status);
		}

		return $query->all();
	}

	/**
	 * Get a single Post entry with its translations
	 *
	 * @param integer $postId
	 *
	 * @return Post|array|null
	 */
	public static function getOneWithTranslations ( $postId )
	{
		return self::find()->id($postId)->withTranslations()->one();
	}

	/**
	 * @param integer            $postId
	 * @param self               $post
	 * @param PostLangEx[]|array $translations
	 *
	 * @return array
	 */
	public static function updateWithTranslations ( $postId, $post, $translations )
	{
		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		//  update the post entry
		$result = self::updatePost($postId, $post);

		//  in case of error, rollback and return error
		if ($result[ "status" ] === self::ERROR) {
			$transaction->rollBack();

			return $result;
		}

		//  create or update all translations
		$result = PostLangEx::manageTranslations($postId, $translations);

		//  if one of the translations status is an error, then return all errors
		if (in_array(PostLangEx::ERROR, ArrayHelperEx::getColumn($result, "status"))) {
			$transaction->rollBack();

			return self::buildError([
				"translations" => ArrayHelperEx::filterInArrayAtIndex(PostLangEx::ERROR, $result, "status")
			]);
		}

		//  commit all changes made to DB
		$transaction->commit();

		//  return updated post
		return self::buildSuccess([ "post" => self::getOneWithTranslations($postId) ]);
	}
}
