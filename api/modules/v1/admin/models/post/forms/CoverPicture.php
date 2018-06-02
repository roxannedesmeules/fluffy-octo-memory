<?php

namespace app\modules\v1\admin\models\post\forms;

use app\modules\v1\admin\models\post\PostLangEx;
use yii\base\Model;

/**
 * Class CoverPicture
 *
 * @package app\modules\v1\admin\models\post\forms
 */
class CoverPicture extends Model
{
	/** @var \yii\web\UploadedFile */
	public $file;

	public function rules ()
	{
		return [
			[ "file", "required" ],
			[
				"file", "file",
				"maxFiles"   => 1,
				"extensions" => PostLangEx::$extensions,
				"maxSize"    => PostLangEx::$maxsize,
				"checkExtensionByMimeType" => false,
			]
		];
	}
}
