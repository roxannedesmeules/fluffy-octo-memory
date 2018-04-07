<?php

namespace app\components\validators;

use app\models\app\Lang;
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
				$icu = Lang::find()->id($form->lang_id)->one()->icu;

				$model->addError($icu, $form->getFirstErrors());
			}
		}
	}
}