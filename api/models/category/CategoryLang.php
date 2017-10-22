<?php
namespace app\models\category;

use app\helpers\ArrayHelperEx;
use app\models\app\Lang;

/**
 * Class CategoryLang
 *
 * @package app\models\category
 */
class CategoryLang extends CategoryLangBase
{
	/**
	 * @param int $categoryId
	 * @param $data
	 *
	 * @return array
	 */
	public static function createTranslation ( $categoryId, $data )
	{
		//  if category doesn't exists, then throw an error
		if (!Category::idExists($categoryId)) {
			return self::buildError(self::ERR_CATEGORY_NOT_FOUND);
		}
		
		//  verify if language in data exists
		if (!Lang::idExists(ArrayHelperEx::getValue($data, "lang_id"))) {
			return self::buildError(self::ERR_LANG_NOT_FOUND);
		}
		
		//  create translation with all attributes from data
		$model = new self();
		
		$model->category_id = $categoryId;
		$model->lang_id     = ArrayHelperEx::getValue($data, 'lang_id');
		$model->name        = ArrayHelperEx::getValue($data, 'name');
		$model->slug        = ArrayHelperEx::getValue($data, 'slug');
		
		//  if the model isn't valid, then return all errors
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}
		
		//  if the model can't be saved, then return generic error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}
		
		//  return success
		return self::buildSuccess([]);
	}
	
	/**
	 * @param int $categoryId
	 *
	 * @return array
	 */
	public static function deleteTranslations ( $categoryId )
	{
		//  define result as success, will be overwritten by an error when necessary
		$result = self::buildSuccess([]);
		
		//  find all translations to be deleted
		$toDelete = self::find()->byCategory($categoryId)->all();
		
		//  delete translations one by one, to correctly trigger the all events
		foreach ( $toDelete as $translation ) {
			if ( !$translation->delete() ) {
				$result = self::buildError(self::ERR_ON_DELETE);
				break;
			}
		}
		
		//  return success
		return $result;
	}
	
	/**
	 * @param int $categoryId
	 * @param int $langId
	 * @param $data
	 *
	 * @return array
	 */
	public static function updateTranslation ( $categoryId, $langId, $data )
	{
		//  verify if the category translation exists
		if ( !self::translationExists($categoryId, $langId) ) {
			return self::buildError(self::ERR_NOT_FOUND);
		}
		
		//  find the model && update all attributes
		$model = self::find()->byCategory($categoryId)->byLang($langId)->one();
		
		$model->name = ArrayHelperEx::getValue($data, "name", $model->name);
		$model->slug = ArrayHelperEx::getValue($data, "slug", $model->slug);
		
		//  if the model isn't valid, then return all errors
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}
		
		//  if the model can't be saved, then return generic error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}
		
		//  return success
		return self::buildSuccess([]);
	}
}