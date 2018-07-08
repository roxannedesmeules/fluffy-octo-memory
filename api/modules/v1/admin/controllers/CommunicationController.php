<?php

namespace app\modules\v1\admin\controllers;

use app\helpers\ArrayHelperEx;
use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\components\parameters as Parameters;
use app\modules\v1\admin\models\communication\CommunicationEx;
use yii\data\ArrayDataProvider;

/**
 * Class CommunicationController
 *
 * @package app\modules\v1\admin\controllers
 *
 * @property array $pagination set from Pagination Parameter
 * @property array $viewed set from Pagination Parameter
 * @property array $replied set from Pagination Parameter
 */
class CommunicationController extends ControllerAdminEx
{
	public $corsMethods = [ "OPTIONS", "GET", "PUT" ];

	/** @inheritdoc */
	public function behaviors ()
	{
		return ArrayHelperEx::merge(parent::behaviors(), [
				"Replied"    => Parameters\Replied::class,
				"Viewed"     => Parameters\Viewed::class,
				"Pagination" => Parameters\Pagination::class,
			]);
	}

	/** @inheritdoc */
	protected function verbs ()
	{
		return [
			"index"  => [ "OPTIONS", "GET" ],
			"view"   => [ "OPTIONS", "GET" ],
			"update" => [ "OPTIONS", "PUT" ],
		];
	}

	/**
	 * This method is used to view all communication message. It will paginate the results to avoid returning too much
	 * data at once and the data will be sorted by the most recently created.
	 *
	 * @return \yii\data\ArrayDataProvider
	 */
	public function actionIndex ()
	{
		$filters = [
			"replied" => $this->replied,
			"viewed"  => $this->viewed,
		];

		$data = [
			"allModels"  => CommunicationEx::getMessages($filters),
			"pagination" => $this->pagination,
		];

		return new ArrayDataProvider($data);
	}

	/**
	 * This method is called to view a single communication message. It will verify that the message ID passed exists,
	 * if it doesn't then an error will be returned, if it does, then the communication message will be returned.
	 *
	 * @param int $id
	 *
	 * @return \app\models\communication\Communication|array|null
	 */
	public function actionView ( $id )
	{
		if (!CommunicationEx::idExists($id)) {
			return $this->error(404, self::ERR_NOT_FOUND);
		}

		return CommunicationEx::find()->byId($id)->one();
	}

	public function actionUpdate ( $id ) {}

	/**
	 * This method will count the number of messages depending on some filters. It will also wrap the response in an
	 * associative array so the returned number makes a little bit of sense.
	 *
	 * @return array
	 */
	public function actionCount ()
	{
		$filters = [
			"replied" => $this->replied,
			"viewed"  => $this->viewed,
		];

		return [ "count" => CommunicationEx::countMessages($filters) ];
	}
}
