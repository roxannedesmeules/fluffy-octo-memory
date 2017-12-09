<?php
namespace app\modules\v1\admin\models\user\forms;

use app\modules\v1\admin\models\user\UserProfileEx;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class UserPicture
 *
 * @package app\modules\v1\admin\models\user\forms
 */
class UserPicture extends Model
{
	/** @var  UploadedFile */
	public $file;

	public function rules ()
	{
		return [
			[ "file", "required" ],
			[
				"file", "file",
				"maxFiles"                 => 1,
				"extensions"               => UserProfileEx::$extensions,
				"maxSize"                  => UserProfileEx::$maxsize,
				"checkExtensionByMimeType" => false,
			]
		];
	}

}
