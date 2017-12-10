<?php
namespace app\models\app;
use app\helpers\ParamsHelper;
use yii\web\UploadedFile;

/**
 * Class File
 *
 * @package app\models\app
 *
 * @property int    $id
 * @property string $name
 * @property string $path
 * @property string $created_on
 * @property int    $is_deleted
 */
class File extends \yii\db\ActiveRecord
{
	const DATE_FORMAT = "Y-m-d H:i:s";

	const NOT_DELETED = 0;
	const DELETED     = 1;

	const FOLDER_POST    = "post";
	const FOLDER_PROFILE = "profile";

	const ERR_ON_UPLOAD = "ERR_ON_FILE_UPLOAD";
	const ERR_ON_SAVE   = "ERR_ON_FILE_SAVE";

	/** @inheritdoc */
	public static function tableName () { return "file"; }

	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ "name", "required" ],
			[ "name", "string", "max" => 255 ],

			[ "path", "required" ],
			[ "path", "string", "max" => 255 ],
			[ "path", "unique" ],

			[ "created_on", "required" ],
			[ "created_on", "safe" ],

			[ "is_deleted", "integer" ],
			[ "is_deleted", "default", "value" => self::NOT_DELETED ],
		];
	}

	/** @inheritdoc */
	public static function find ()
	{
		return new FileQuery(get_called_class());
	}

	/**
	 * @param \yii\web\UploadedFile $file
	 * @param sting                 $path
	 *
	 * @return string|int
	 */
	protected static function addFile ( $file, $path )
	{
		$model = new File();

		$model->name       = $file->getBaseName() . "." . $file->getExtension();
		$model->path       = $path;
		$model->created_on = date(self::DATE_FORMAT);
		$model->is_deleted = self::NOT_DELETED;

		if (!$model->save()) {
			return self::ERR_ON_SAVE;
		}

		return $model->id;
	}

	/**
	 * Create the full path to be used in order to access the file from the outside.
	 *
	 * @return string
	 */
	public function getFullPath ()
	{
		$url  = ParamsHelper::get("domainName");
		$url .= self::BASEPATH;
		$url .= $this->path;

		return $url;
	}

	/**
	 * This method will create a random string as filename to avoid overwriting files. A verification will obviously be
	 * made to make sure it doesn't already exists. The filename and verification will be made over and over again until
	 * the filename isn't found.
	 *
	 * @param string $extension
	 *
	 * @return string
	 * @throws \yii\base\Exception
	 */
	private static function generateFilename ( $extension )
	{
		do {
			$filename      = \Yii::$app->getSecurity()->generateRandomString(32);
			$filename     .= ".$extension";
			$alreadyExists = self::find()->andWhere([ "LIKE", "path", $filename ])->exists();
		} while ($alreadyExists);

		return $filename;
	}

	/**
	 * Mark a file as deleted. The file isn't actually deleted to avoid deleting the wrong file, but this flag is set so
	 * we know it can later be removed during cleanup.
	 */
	public function markAsDeleted ()
	{
		$this->is_deleted = self::DELETED;

		$this->save();
	}

	/**
	 * @param UploadedFile $file
	 *
	 * @return int|string
	 * @throws \yii\base\Exception
	 */
	public static function uploadProfileLocally ( $file )
	{
		$filename = self::generateFilename($file->getExtension());
		$path     = self::FOLDER_PROFILE . "/$filename";

		if (!$file->saveAs(\Yii::getAlias("@upload/$path"))) {
			return self::ERR_ON_UPLOAD;
		}

		return self::addFile($file, $path);
	}
}
