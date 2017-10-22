<?php

namespace app\components\validators;

use yii\validators\Validator;

/**
 * Class Translations
 *
 * @package common\components\validators
 */
class TranslationValidator extends Validator
{
	/** @var \yii\base\Model the validator to use on the translation */
	public $validator;
	
	/**
	 * @param \yii\base\Model $model
	 * @param string          $attribute
	 */
	public function validateAttribute ( $model, $attribute )
	{
		foreach ( $model->$attribute as $i => $translation ) {
			
			if ($translation instanceof $this->validator) {
				$form = $translation;
			} else {
				$form = new $this->validator();
				$form->attributes = $translation;
				
			}
			
			if ( !$form->validate() ) {
				$model->addErrors([ $attribute => [ array_merge(... [ $form->getErrors() ]) ] ]);
			}
		}
	}
}