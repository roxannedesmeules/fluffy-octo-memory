<?php

namespace app\models\app;

use Yii;

/**
 * This is the model class for table "api_client".
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property string $key
 */
class ApiClient extends \yii\db\ActiveRecord
{
	
	const ADMIN = "Admin";
	const BLOG  = "Blog";
	
	/**
	 * @inheritdoc
	 */
	public static function tableName ()
	{
		return 'api_client';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules ()
	{
		return [
			[ [ 'name', 'description', 'key' ], 'required' ],
			[ [ 'description' ], 'string' ],
			[ [ 'name' ], 'string', 'max' => 255 ],
			[ [ 'key' ], 'string', 'max' => 32 ],
			[ [ 'name' ], 'unique' ],
			[ [ 'key' ], 'unique' ],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels ()
	{
		return [
			'id'          => Yii::t('app.api', 'ID'),
			'name'        => Yii::t('app.api', 'Name'),
			'description' => Yii::t('app.api', 'Description'),
			'key'         => Yii::t('app.api', 'Key'),
		];
	}
	
	/**
	 * @inheritdoc
	 * @return ApiClientQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new ApiClientQuery(get_called_class());
	}
	
	/**
	 * @param $key
	 *
	 * @return ApiClient|array|null
	 */
	public static function findAdminKey ( $key )
	{
		return self::find()->name(self::ADMIN)->key($key)->one();
	}
}
