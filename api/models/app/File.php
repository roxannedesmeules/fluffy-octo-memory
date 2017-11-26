<?php
namespace app\models\app;

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

		$model->name       = $file->getBaseName();
		$model->path       = $path;
		$model->created_on = date(self::DATE_FORMAT);
		$model->is_deleted = self::NOT_DELETED;

		if ($model->save()) {
			return self::ERR_ON_SAVE;
		}

		return $model->id;
	}

	/**
	 * @param string $folder
	 * @param string $filename
	 *
	 * @return bool|string
	 */
	private static function generateLocalPath ( $folder, $filename )
	{
		return \Yii::getAlias("@upload/$folder/$filename");
	}

	/**
	 * @return string
	 */
	private static function generateFilename ( $extension )
	{
		do {
			$filename      = \Yii::$app->getSecurity()->generateRandomString(32);
			$filename     .= ".$extension";
			$alreadyExists = self::find()->andWhere([ "LIKE", "path", $filename ])->exists();
		} while (!$alreadyExists);

		return $filename;
	}

	/**
	 *
	 */
	public function markAsDeleted ()
	{
		$this->is_deleted = self::DELETED;

		$this->save();
	}

	/**
	 * @param \yii\web\UploadedFile $file
	 *
	 * @return bool
	 */
	public static function uploadProfileLocally ( $file )
	{
		$filename = self::generateFilename($file->getExtension());
		$path     = self::generateLocalPath(self::FOLDER_PROFILE, $filename);

		if (!$file->saveAs($path)) {
			return self::ERR_ON_UPLOAD;
		}

		return self::addFile($file, $path);
	}
}
