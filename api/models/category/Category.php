<?php
namespace app\models\category;
use app\helpers\ArrayHelperEx;

/**
 * Class Category
 *
 * @package app\models\category
 */
class Category extends CategoryBase
{
	/**
	 * @param $data
	 *
	 * @return array
	 */
	public static function createCategory ( $data )
	{
		//  create a model
		$model = new self();
		
		//  assign attributes
		$model->is_active = ArrayHelperEx::getValue($data, "is_active");
		
		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}
		
		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}
		
		//  return the category_id
		return self::buildSuccess([ "category_id" => $model->id ]);
	}

	/**
	 * @param $categoryId
	 *
	 * @return array
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public static function deleteCategory ( $categoryId )
	{
		//  check if the category ID exists
		if ( !self::idExists($categoryId) ) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find the model to delete
		$model = self::find()->id($categoryId)->one();

		if ($model->is_active) {
			return self::buildError(self::ERR_DELETE_ACTIVE);
		}

		//  delete model, in case of error, return it
		if ( !$model->delete() ) {
			return self::buildError(self::ERR_ON_DELETE);
		}
		
		//  return success
		return self::buildSuccess([]);
	}
	
	/**
	 * @param int $categoryId
	 * @param $data
	 *
	 * @return array
	 */
	public static function updateCategory ( $categoryId, $data )
	{
		//  check if the category ID exists
		if ( !self::idExists($categoryId) ) {
			return self::buildError(self::ERR_NOT_FOUND);
		}
		
		//  find the model to update
		$model = self::find()->id($categoryId)->one();
		
		//  assign all attributes
		$model->is_active = ArrayHelperEx::getValue($data, "is_active");
		
		// if the model doesn't validate, return error
		if ( !$model->validate() ) {
			return self::buildError($model->getErrors());
		}
		
		// if the model doesn't save, then return error
		if ( !$model->save() ) {
			return self::buildError(self::ERR_ON_SAVE);
		}
		
		//  return success
		return self::buildSuccess([]);
	}
}