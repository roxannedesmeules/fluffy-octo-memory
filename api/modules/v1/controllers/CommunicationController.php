<?php

namespace app\modules\v1\controllers;

use app\modules\v1\components\ControllerEx;
use app\modules\v1\models\CommunicationEx;

/**
 * Class CommunicationController
 *
 * @package app\modules\v1\controllers
 */
class CommunicationController extends ControllerEx
{
	public $corsMethods = [ "OPTIONS", "POST" ];

	/** @inheritdoc */
	protected function verbs ()
	{
		return [
			"create" => [ "OPTIONS", "POST" ],
		];
	}

	public function actionCreate ()
	{
		$form = new CommunicationEx();

		$form->setAttributes($this->request->getBodyParams());

		if (!$form->validate()) {
			return $this->error(422, $form->getErrors());
		}

		$result = CommunicationEx::createMessage($form);

		if ($result[ "status" ] === CommunicationEx::ERROR) {
			if (is_array($result[ "error" ])) {
				return $this->error(422, $result[ "error" ]);
			} else {
				return $this->error(400, $result[ "error" ]);
			}
		}

		$this->response->setStatusCode(201);
	}
}
