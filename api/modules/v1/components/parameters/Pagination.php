<?php
namespace app\modules\v1\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Pagination
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Pagination extends \yii\base\Behavior
{
	const PAGE_NUMBER = 0;
	const PAGE_SIZE   = 10;

	const PAGE_SIZE_MIN = 0;
	const PAGE_SIZE_MAX = 100;

	const NO_PAGINATION = -1;

	/** @var pagination     contains */
	public $pagination;

	/** @inheritdoc */
	public function events ()
	{
		return ArrayHelperEx::merge(parent::events(),
									[
										Controller::EVENT_BEFORE_ACTION => "getPagination",
									]);
	}

	public function getPagination ( $event )
	{
		$request = \Yii::$app->request;

		//  get the page number parameter
		$this->pagination = [
			"page"     => (int) $request->get("page", self::PAGE_NUMBER),
			"pageSize" => (int) $request->get("per-page", self::PAGE_SIZE),
		];

		if ($this->pagination[ "pageSize" ] !== self::NO_PAGINATION) {
			//  verify that the page size doesn't ask for more result than the maximum
			if ($this->pagination[ "pageSize" ] > self::PAGE_SIZE_MAX) {
				$this->pagination[ "pageSize" ] = self::PAGE_SIZE_MAX;
			}

			//  verify that the page size doesn't ask for no result at all either
			if ($this->pagination[ "pageSize" ] <= self::PAGE_SIZE_MIN) {
				$this->pagination[ "pageSize" ] = self::PAGE_SIZE;
			}
		}
	}

}
