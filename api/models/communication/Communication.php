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

	/**
	 * This method will update a specific message in the communication table. To keep the integrity of the data received,
	 * only viewed and replied flags are allowed to be updated.
	 *
	 * @param int $id
	 * @param $data
	 *
	 * @return array
	 */
	public static function updateMessage ( $id, $data )
	{
		if (!self::idExists($id)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		$model = self::find()->byId($id)->one();

		$model->is_replied = ArrayHelperEx::getValue($data, "is_replied", $model->is_replied);
		$model->is_viewed  = ArrayHelperEx::getValue($data, "is_viewed", ($model->is_replied) ? $model->is_replied : $model->is_viewed);

		if (!$model->validate()) {
			return self::buildError($model->getErrors());
		}

		if (!$model->save()) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		return self::buildSuccess([]);
	}
}
