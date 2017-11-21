<?php
namespace app\models\app;

/**
 * Class File
 *
 * @package app\models\app
 */
class File extends \yii\db\ActiveRecord
{
	const DATE_FORMAT = "Y-m-d H:i:s";

	const NOT_DELETED = 0;
	CONST DELETED     = 1;

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

	public static function find ()
	{
		return new FileQuery(get_called_class());
	}
}
