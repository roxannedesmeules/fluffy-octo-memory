<?php

namespace app\modules\v1\admin\models;

use app\helpers\DateHelper;
use app\models\app\File;

/**
 * Class FileEx
 *
 * @package app\modules\v1\admin\models
 */
class FileEx extends File
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "File",
	 *
	 *     @SWG\Property( property = "id",   type = "integer" ),
	 *     @SWG\Property( property = "name", type = "string" ),
	 *     @SWG\Property( property = "path", type = "string" ),
	 *     @SWG\Property( property = "created_on", type = "string", format = "datetime" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"id",
			"name",
			"path"       => function ( self $model ) { return $model->getFullPath(); },
			"created_on" => function ( self $model ) { return DateHelper::formatDate($model->created_on, DateHelper::DATETIME_FORMAT); },
		];
	}
}
