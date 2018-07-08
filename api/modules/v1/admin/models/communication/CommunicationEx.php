<?php

namespace app\modules\v1\admin\models\communication;

use app\helpers\DateHelper;
use app\models\communication\Communication;

/**
 * Class CommunicationEx
 *
 * @package app\modules\v1\admin\models\communication
 */
class CommunicationEx extends Communication
{
	/** @inheritdoc */
	public function fields ()
	{
		return [
			"id",
			"name",
			"email",
			"subject",
			"message",
			"is_viewed",
			"is_replied",
			"created_on" => function ( self $model ) { return DateHelper::formatDate($model->created_on); },
		];
	}

	/**
	 * @param array $filters
	 *
	 * @return self[]|array
	 */
	public static function getMessages ( $filters )
	{
		$query = self::find()->mostRecent();

		if ($filters[ "viewed" ] !== -1) {
			$query->andWhere([ "is_viewed" => $filters[ "viewed" ] ]);
		}

		if ($filters[ "replied" ] !== -1) {
			$query->andWhere([ "is_replied" => $filters[ "replied" ] ]);
		}

		return $query->all();
	}

	/**
	 * @param array $filters
	 *
	 * @return int|string
	 */
	public static function countMessages ( $filters )
	{
		$query = self::find();

		if ($filters[ "viewed" ] !== -1) {
			$query->andWhere([ "is_viewed" => $filters[ "viewed" ] ]);
		}

		if ($filters[ "replied" ] !== -1) {
			$query->andWhere([ "is_replied" => $filters[ "replied" ] ]);
		}

		return $query->count();
	}
}
