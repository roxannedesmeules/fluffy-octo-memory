<?php
namespace app\modules\v1\admin\models\category;

use app\components\validators\ArrayUniqueValidator;
use app\components\validators\TranslationValidator;
use app\models\category\Category;

/**
 * Class CategoryEx
 *
 * @package app\modules\v1\admin\models\category
 *
 * @SWG\Definition(
 *     definition = "Categories",
 *     type = "array",
 *
 *     @SWG\Items( ref = "#/definitions/Category" ),
 * )
 *
 * @property array $translations
 */
class CategoryEx extends Category
{
	/** @var array */
	public $translations = [];

	/** @inheritdoc */
	public function getCategoryLangs ()
	{
		return $this->hasMany(CategoryLangEx::className(), [ "category_id" => "id" ]);
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "Category",
	 *
	 *     @SWG\Property( property = "id", type = "integer" ),
	 *     @SWG\Property( property = "is_active", type = "integer" ),
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/CategoryTranslation" ) ),
	 *     @SWG\Property( property = "created_on", type = "string" ),
	 *     @SWG\Property( property = "updated_on", type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"id",
			"is_active",
			"translations" => "categoryLangs",
			"created_on",
			"updated_on",
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *     definition = "CategoryForm",
	 *     required   = { "translations" },
	 *
	 *     @SWG\Property( property = "is_active", type = "integer" ),
	 *     @SWG\Property( property = "translations", type = "array", @SWG\Items( ref = "#/definitions/CategoryTranslationForm" ) ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "is_active", "integer", "message" => self::ERR_FIELD_TYPE ],
			[ "is_active", "default", "value" => self::INACTIVE ],
			
			[ "translations", "required", "strict" => true, "message" => self::ERR_FIELD_REQUIRED ],
			[ "translations", TranslationValidator::className(), "validator" => CategoryLangEx::className() ],
			[ "translations", ArrayUniqueValidator::className(), "uniqueKey" => "lang_id", "message" => self::ERR_FIELD_UNIQUE_LANG ],
		];
	}
	
	/**
	 * @param $data
	 * @param $translations
	 *
	 * @return array
	 */
	public static function createWithTranslations ( $data, $translations )
	{
		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();
		
		//  create category entry
		$result = self::createCategory($data);
		
		//  in case of error, rollback and return error
		if ($result[ "status" ] === self::ERROR) {
			$transaction->rollBack();
			
			return $result;
		}
		
		//  keep the category ID
		$categoryId = $result[ "category_id" ];
		
		//  create all translations
		$result = CategoryLangEx::manageTranslations($categoryId, $translations);
		
		//  in case of error, rollback and return error
		if ($result[ "status" ] === CategoryLangEx::ERROR) {
			$transaction->rollBack();
			
			return self::buildError([ "translations" => $result[ "error" ] ]);
		}
		
		//  commit translations
		$transaction->commit();
		
		//  return category ID
		return self::buildSuccess([ "category_id" => $categoryId ]);
	}
	
	/**
	 * @param int $categoryId
	 *
	 * @return array
	 */
	public static function deleteWithTranslations ( $categoryId )
	{
		//  set the $db property
		self::defineDbConnection();

		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();

		if (self::hasPosts($categoryId)) {
			$transaction->rollBack();

			return self::buildError(self::ERR_DELETE_POSTS);
		}
		
		//  delete translation first
		$result = CategoryLangEx::deleteTranslations($categoryId);
		
		//  in case of error, rollback and return error
		if ($result[ "status" ] === CategoryLangEx::ERROR) {
			$transaction->rollBack();
			
			return self::buildError([ "translations" => $result[ "error" ] ]);
		}
		
		//  delete category
		$result = self::deleteCategory($categoryId);
		
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
	 * @return \app\models\category\CategoryBase[]|array
	 */
	public static function getAllWithTranslations ()
	{
		return self::find()->withTranslations()->all();
	}
	
	/**
	 * @param $categoryId
	 *
	 * @return \app\models\category\CategoryBase[]|array
	 */
	public static function getOneWithTranslations ( $categoryId )
	{
		return self::find()->id($categoryId)->withTranslations()->one();
	}
	
	/**
	 * @param $categoryId
	 * @param $data
	 * @param $translations
	 *
	 * @return array
	 */
	public static function updateWithTranslations ( $categoryId, $data, $translations )
	{
		//  start a transaction to rollback at any moment if there is a problem
		$transaction = self::$db->beginTransaction();
		
		//  update the category
		$result = self::updateCategory($categoryId, $data);
		
		//  in case of error, rollback and return error
		if ($result[ "status" ] === self::ERROR) {
			$transaction->rollBack();
			
			return $result;
		}
		
		//  update or create all translations for this category
		$result = CategoryLangEx::manageTranslations($categoryId, $translations);
		
		//  in case of error, rollback and return error
		if ($result[ "status" ] === CategoryLangEx::ERROR) {
			$transaction->rollBack();

			return self::buildError([ "translations" => $result[ "error" ] ]);
		}
		
		//  commit translations
		$transaction->commit();
		
		//  return the updated Category
		$category = self::find()->id($categoryId)->withTranslations()->one();
		
		return self::buildSuccess([ "category" => $category ]);
	}
}
