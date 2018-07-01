<?php

namespace app\models\communication;

use app\helpers\ArrayHelperEx;

/**
 * Class Communication
 *
 * @package app\models\communication
 */
class Communication extends CommunicationBase
{
	/**
	 * This method will create a single message in the communication table.
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public static function createMessage ( $data )
	{
		$model = new self();

		$model->name    = ArrayHelperEx::getValue($data, "name");
		$model->email   = ArrayHelperEx::getValue($data, "email");
		$model->subject = ArrayHelperEx::getValue($data, "subject");
		$model->message = ArrayHelperEx::getValue($data, "message");

		if (!$model->validate()) {
			return self::buildError($model->getErrors());
		}

		if (!$model->save()) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		return self::buildSuccess([ "id" => $model->id ]);
	}
}
