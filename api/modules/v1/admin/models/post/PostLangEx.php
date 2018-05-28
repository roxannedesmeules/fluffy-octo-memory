<?php
namespace app\modules\v1\admin\models\post;

use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;
use app\models\app\Lang;
use app\models\post\PostLang;
use app\modules\v1\admin\models\FileEx;
use app\modules\v1\admin\models\LangEx;
use app\modules\v1\admin\models\user\UserEx;
use yii\web\UploadedFile;

/**
 * Class PostLangEx
 *
 * @package app\modules\v1\admin\models\post
 */
class PostLangEx extends PostLang
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostTranslation",
	 *
	 *     @SWG\Property( property = "language",  ref  = "#/definitions/Lang" ),
	 *     @SWG\Property( property = "title",     type = "string" ),
	 *     @SWG\Property( property = "slug",      type = "string" ),
	 *     @SWG\Property( property = "summary",   type = "string" ),
	 *     @SWG\Property( property = "content",   type = "string" ),
	 *     @SWG\Property( property = "cover",     ref  = "#/definitions/File" ),
	 *     @SWG\Property( property = "cover_alt", type = "string" ),
	 *     @SWG\Property( property = "author",    type = "object", 
	 *          @SWG\Property( property = "id", type = "integer" ),
	 *          @SWG\Property( property = "fullname", type = "string" ),
	 *     ),
	 *     @SWG\Property( property = "created_on", type = "string" ),
	 *     @SWG\Property( property = "updated_on", type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"language"  => "lang",
			"title",
			"slug",
			"summary",
			"content",
			"cover"     => "file",
			"cover_alt" => "file_alt",
			"author"    => function ( self $model ) {
				return [ "id" => $model->user_id, "fullname" => $model->user->userProfile->getFullname() ];
			},
			"created_on"   => function ( self $model ) { return DateHelper::formatDate($model->created_on); },
			"updated_on"   => function ( self $model ) { return DateHelper::formatDate($model->updated_on); },
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostTranslationForm",
	 *       required   = { "lang_id", "title", "slug", "content" },
	 *
	 *     @SWG\Property( property = "tag_id",  type = "integer" ),
	 *     @SWG\Property( property = "lang_id", type = "integer" ),
	 *     @SWG\Property( property = "title",   type = "string" ),
	 *     @SWG\Property( property = "slug",    type = "string" ),
	 *     @SWG\Property( property = "content", type = "string" ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "post_id", "number" ],

			[ "lang_id", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[
				"lang_id", "exist",
				"targetClass"     => Lang::className(),
				"targetAttribute" => [ "lang_id" => "id" ],
				"message"         => self::ERR_FIELD_NOT_FOUND,
			],

			[ "title", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "title", "string", "max" => 255, "tooLong" => self::ERR_FIELD_TOO_LONG ],

			[ "slug", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "slug", "string", "max" => 255, "tooLong" => self::ERR_FIELD_TOO_LONG ],
			[
				"slug", "unique",
				"targetAttribute" => [ "slug", "lang_id" ],
				"message"         => self::ERR_FIELD_NOT_UNIQUE,
				"when"            => function ( self $model, string $attribute ) {
					$found = self::find()->byLang($model->lang_id)->where([ $attribute => $model->$attribute ])->one();

					return ($found) ? ($model->post_id !== $found->post_id) : true;
				},
			],

			[ "summary", "string", "max" => 180, "tooLong" => self::ERR_FIELD_TOO_LONG ],

			[ "content", "string", "message" => self::ERR_FIELD_TYPE ],

			[ "file_alt", "string", "max" => 255, "tooLong" => self::ERR_FIELD_TOO_LONG ],
		];
	}

	/** @inheritdoc */
	public function getFile ()
	{
		return $this->hasOne(FileEx::className(), [ "id" => "file_id" ]);
	}

	/** @inheritdoc */
	public function getUser ()
	{
		return $this->hasOne(UserEx::className(), [ "id" => "user_id" ]);
	}

	public static function deleteCoverPicture ( int $postId, int $langId )
	{
		//  check if the post translation exists
		if (!self::translationExists($postId, $langId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		/** @var self $model */
		$model = self::find()->byPost($postId)->byLang($langId)->one();

		if ($model->hasCover()) {
			$result = $model->deleteCover(true);

			if ($result !== self::SUCCESS) {
				return self::buildError(self::ERR_ON_COVER_DELETE);
			}
		}

		return self::buildSuccess([]);
	}

	/**
	 * @param integer $postId
	 * @param self[]  $translations
	 *
	 * @return array
	 */
	public static function manageTranslations ( $postId, $translations )
	{
		//  if the post doesn't exists, then return an error
		if (!PostEx::idExists($postId)) {
			return self::buildError(self::ERR_POST_NOT_FOUND);
		}

		//  define result as success, it will be overwritten by an error when necessary
		$result = [];

		//  define which translations needs to be created and which ones needs to be updated
		foreach ($translations as $idx => $translation) {
			$langId = ArrayHelperEx::getValue($translation, "lang_id");

			//  verify if the language exists, return an error if it doesn't
			if (!LangEx::idExists($langId)) {
				$result[ $idx ] = self::buildError(self::ERR_LANG_NOT_FOUND);
				continue;
			}

			//  if the translation exists, then update it, otherwise create it
			if (self::translationExists($postId, $langId)) {
				$result[ $idx ] = self::updateTranslation($postId, $langId, $translation);
			} else {
				$result[ $idx ] = self::createTranslation($postId, $translation);
			}
		}

		//  return result of each translation
		return $result;
	}

	/**
	 * This method will verify that the PostLang entry exists in the database. It will delete the cover picture if there
	 * is one already existing, then it will upload the new one.
	 *
	 * @param int          $postId
	 * @param int          $langId
	 * @param UploadedFile $file
	 *
	 * @return array|int
	 * @throws \yii\base\Exception
	 */
	public static function manageCoverPicture (int $postId, int $langId, UploadedFile $file)
	{
		//  check if the post translation exists
		if (!self::translationExists($postId, $langId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		/** @var self $model */
		$model = self::find()->byPost($postId)->byLang($langId)->one();

		//  soft delete the existing cover if one
		if ($model->hasCover()) {
			$result = $model->deleteCover();

			if ($result !== self::SUCCESS) {
				return self::buildError(self::ERR_ON_COVER_UPDATE);
			}
		}

		//  upload the new cover
		$result = $model->uploadCover($file);

		//  return the result
		return $result;
	}
}
