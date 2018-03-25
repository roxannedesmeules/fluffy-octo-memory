<?php

namespace app\modules\v1\admin\models\tag;

use app\components\validators\ArrayUniqueValidator;
use app\components\validators\TranslationValidator;
use app\helpers\DateHelper;
use app\models\tag\Tag;

/**
 * Class TagEx
 *
 * @package app\modules\v1\admin\models\tag
 *          
 * @SWG\Definition(
 *     definition = "TagList",
 *     type       = "array",
 *     @SWG\Items( ref = "#/definitions/Tag" )
 * )
 *
 * @property array $translations
 */
class TagEx extends Tag
{
	const ERR_FIELD_REQUIRED    = "ERR_FIELD_REQUIRED";
	const ERR_FIELD_UNIQUE_LANG = "ERR_FIELD_UNIQUE_LANG";

	/** @var array  */
	public $translations = [];

	/** @inheritdoc */
	public function getTagLangs ()
	{
		return $this->hasMany(TagLangEx::className(), [ "tag_id" => "id" ]);
	}

	/**
	 * @inheritdoc
	 * 
	 * @SWG\Definition(
	 *     definition = "Tag",
	 *     
	 *     @SWG\Property( property = "id",           type = "integer" ),
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/TagTranslation" ) ),
	 *     @SWG\Property( property = "created_on",   type = "string" ),
	 *     @SWG\Property( property = "updated_on",   type = "string" ),
	 *     @SWG\Property( property = "links",        type = "array", @SWG\Items( ref = "#/definitions/HATEOAS" ) ),
	 * )
	 */
	public function fields ()
	{
		return [
			"id",
			"translations" => "tagLangs",
			"created_on"   => function ( self $model ) { return DateHelper::formatDate($model->created_on, self::DATE_FORMAT); },
			"updated_on"   => function ( self $model ) { return DateHelper::formatDate($model->updated_on, self::DATE_FORMAT); },
		];
	}

	/**
	 * @inheritdoc
	 * 
	 * @SWG\Definition(
	 *     definition = "TagForm",
	 *     required   = { "translations" },
	 *     
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/TagTranslationForm" ) ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "translations", "required", "strict" => true, "message" => self::ERR_FIELD_REQUIRED ],
			[ "translations", TranslationValidator::className(), "validator" => TagLangEx::className() ],
			[ "translations", ArrayUniqueValidator::className(), "uniqueKey" => "lang_id", "message" => self::ERR_FIELD_UNIQUE_LANG],
		];
	}

	/**
	 * @param $translations
	 *
	 * @return array
	 * @throws \yii\db\Exception
	 */
	public static function createWithTranslations ( $translations )
	{
		//  start transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		//  create tag entry
		$result = self::createTag();

		//  in case of error, roll back and return error
		if ( $result[ "status" ] === self::ERROR ) {
			$transaction->rollBack();

			return $result;
		}

		//  keep the tag ID for future use
		$tagId = $result[ "tag_id" ];

		//  create all translations
		$result = TagLangEx::manageTranslations($tagId, $translations);

		//  in case of error, rollback and return error
		if ($result[ "status" ] === TagLangEx::ERROR) {
			$transaction->rollBack();

			return self::buildError([ "translations" => $result[ "error" ] ]);
		}

		//  keep changes in database
		$transaction->commit();

		//  return tag ID
		return self::buildSuccess([ "tag_id" => $tagId ]);
	}

	/**
	 * @param $tagId
	 *
	 * @return array
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\Exception
	 * @throws \yii\db\StaleObjectException
	 */
	public static function deleteWithTranslations ( $tagId )
	{
		//  set the $db property
		self::defineDbConnection();

		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		//  if the tag is linked to published post, then don't delete it and return an error
		if (self::hasPublishedPosts($tagId)) {
			$transaction->rollBack();

			return self::buildError(self::ERR_DELETE_POSTS);
		}

		//  delete translations first, to avoid foreign key issues
		$result = TagLangEx::deleteTranslations($tagId);

		//  in case of error, rollback and return error
		if ( $result[ "status" ] === TagLangEx::ERROR ) {
			$transaction->rollBack();

			return self::buildError([ "translations" => $result[ "error" ] ]);
		}

		//  delete all post association
		$result = AssoTagPostEx::deleteAllForTag($tagId);

		//  in case of error, rollback and return error
		if ( $result[ "status" ] === AssoTagPostEx::ERROR ) {
			$transaction->rollBack();

			return $result;
		}

		//  delete tag it self
		$result = self::deleteTag($tagId);

		//  in case of error, rollback and return error
		if ( $result[ "status" ] === self::ERROR ) {
			$transaction->rollBack();

			return $result;
		}

		//  keep changes in database
		$transaction->commit();

		//  return success
		return self::buildSuccess([]);
	}

	/**
	 * @param $tagId
	 * @param $translations
	 *
	 * @return array
	 * @throws \yii\db\Exception
	 */
	public static function updateWithTranslations ( $tagId, $translations )
	{
		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		//  update the tag itself
		$result = self::updateTag($tagId);

		//  in case of error, rollback and return error
		if ( $result[ "status" ] === self::ERROR ) {
			$transaction->rollBack();

			return $result;
		}

		// update all tag translations
		$result = TagLangEx::manageTranslations($tagId, $translations);

		//  in case of error, rollback and return error
		if ( $result[ "status" ] === TagLangEx::ERROR ) {
			$transaction->rollBack();

			return self::buildError([ "translations" => $result[ "error" ] ]);
		}


		//  keep changes in database
		$transaction->commit();

		//  get the updated tag object with translations
		$tag = self::find()->id($tagId)->withTranslations()->one();

		//  return success
		return self::buildSuccess([ "tag" => $tag ]);
	}
}
