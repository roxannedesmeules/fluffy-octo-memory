<?php
namespace app\components\validators;

use app\helpers\ArrayHelperEx;
use yii\validators\Validator;


/**
 * Class ArrayUnique
 *
 * @package common\components\validators
 */
class ArrayUniqueValidator extends Validator
{
	/** @var  string    index where the value must be unique in each sub-array */
	public $uniqueKey;
	
	/** @var  string    error message */
	public $message;
	
	public function validateAttribute ( $model, $attribute )
	{
		$values = ArrayHelperEx::getColumn($model->$attribute, $this->key);
		
		if (ArrayHelperEx::hasDuplicates($values)) {
			$model->addError($attribute, $this->message);
		}
	}
}