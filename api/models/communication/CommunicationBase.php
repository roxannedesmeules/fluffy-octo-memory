<?php

namespace app\models\communication;

use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;

/**
 * This is the model class for table "{{%communication}}".
 *
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property int    $is_viewed
 * @property int    $is_replied
 * @property string $created_on
 */
abstract class CommunicationBase extends \yii\db\ActiveRecord
{
	const NOT_REPLIED = 0;
	const REPLIED     = 1;

	const NOT_VIEWED = 0;
	const VIEWED     = 1;

	const ERROR   = 0;
	const SUCCESS = 1;

	const ERR_NOT_FOUND = "ERR_NOT_FOUND";
	const ERR_ON_SAVE = "ERR_ON_SAVE";

	const ERR_FIELD_REQUIRED = "ERR_FIELD_REQUIRED";
	const ERR_FIELD_TYPE     = "ERR_FIELD_WRONG_TYPE";
	const ERR_FIELD_LONG     = "ERR_FIELD_TOO_LONG";

	/** @inheritdoc */
	public static function tableName () { return '{{%communication}}'; }

	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ [ 'name', 'email', 'message' ], 'required' ],
			[ [ 'message' ], 'string' ],
			[ [ 'is_replied', 'is_viewed' ], 'integer' ],
			[ [ 'created_on' ], 'safe' ],
			[ [ 'name', 'email', 'subject' ], 'string', 'max' => 255 ],
		];
	}

	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'id'         => 'ID',
			'name'       => 'Name',
			'email'      => 'Email',
			'subject'    => 'Subject',
			'message'    => 'Message',
			'is_viewed'  => 'Is Viewed',
			'is_replied' => 'Is Replied',
			'created_on' => 'Created On',
		];
	}

	/**
	 * @inheritdoc
	 * @return CommunicationQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new CommunicationQuery(get_called_class());
	}

	/** @inheritdoc */
	public function beforeSave ( $insert )
	{
		if ($insert) {
			$this->created_on = date(DateHelper::DATETIME_FORMAT);
			$this->is_viewed  = self::NOT_VIEWED;
			$this->is_replied = self::NOT_REPLIED;
		}

		return parent::beforeSave($insert);
	}

	/**
	 * This method will check if a specific communication ID exists.
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public static function idExists ( $id )
	{
		return self::find()->byId($id)->exists();
	}

	/**
	 * Build an array to use when returning from another method. The status will automatically
	 * set to ERROR, then $error passed in param will be associated to the error key.
	 *
	 * @param $error
	 *
	 * @return array
	 */
	public static function buildError ( $error )
	{
		return [ "status" => self::ERROR, "error" => $error ];
	}

	/**
	 * Build an array to use when returning from another method. The status will be automatically
	 * set to SUCCESS, then the $params will be merged with the array and be returned.
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public static function buildSuccess ( $params )
	{
		return ArrayHelperEx::merge([ "status" => self::SUCCESS ], $params);
	}
}
