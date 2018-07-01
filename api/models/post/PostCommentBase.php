<?php

namespace app\models\post;

use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;
use app\models\app\Lang;
use app\models\user\User;

/**
 * This is the model class for table "{{%post_comment}}".
 *
 * @property int    $id
 * @property int    $post_id
 * @property int    $lang_id
 * @property int    $reply_comment_id
 * @property int    $user_id
 * @property string $author
 * @property string $email
 * @property string $comment
 * @property int    $is_approved
 * @property string $created_on
 * @property string $approved_on
 *
 * @property Lang          $lang
 * @property Post          $post
 * @property PostComment   $replyTo
 * @property PostComment[] $replies
 * @property User          $user
 */
abstract class PostCommentBase extends \yii\db\ActiveRecord
{
	const NOT_APPROVED = 0;
	const IS_APPROVED  = 1;

	const ERROR   = 0;
	const SUCCESS = 1;

	const ERR_ON_SAVE           = "ERR_COMMENT_ON_SAVE";
	const ERR_POST_NOT_FOUND    = "ERR_POST_NOT_FOUND";
	const ERR_COMMENT_NOT_FOUND = "ERR_COMMENT_NOT_FOUND";
	const ERR_ON_DELETE          = "ERR_ON_DELETE";
	const ERR_DELETE_HAS_REPLIES = "ERR_DELETE_HAS_REPLIES";

	const ERR_FIELD_REQUIRED    = "ERR_FIELD_VALUE_REQUIRED";
	const ERR_FIELD_TYPE        = "ERR_FIELD_VALUE_WRONG_TYPE";
	const ERR_FIELD_NOT_FOUND   = "ERR_FIELD_VALUE_NOT_FOUND";
	const ERR_FIELD_UNIQUE_LANG = "ERR_FIELD_UNIQUE_LANG";
	const ERR_FIELD_TOO_LONG    = "ERR_FIELD_TOO_LONG";

	/** @inheritdoc */
	public static function tableName () { return '{{%post_comment}}'; }

	/** @inheritdoc */
	public function rules ()
	{
		return [
			[ [ 'post_id', 'lang_id', 'comment' ], 'required' ],
			[ [ 'post_id', 'lang_id', 'is_approved' ], 'integer' ],
			[ [ 'comment' ], 'string' ],
			[ [ 'created_on', "approved_on" ], 'safe' ],
			[ [ 'author' ], 'string', 'max' => 140 ],
			[ [ 'email' ], 'string', 'max' => 255 ],
			[
				[ 'lang_id' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Lang::className(),
				'targetAttribute' => [ 'lang_id' => 'id' ],
			],
			[
				[ 'post_id' ],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Post::className(),
				'targetAttribute' => [ 'post_id' => 'id' ],
			],
		];
	}

	/** @inheritdoc */
	public function attributeLabels ()
	{
		return [
			'id'          => 'ID',
			'post_id'     => 'Post ID',
			'lang_id'     => 'Lang ID',
			'author'      => 'Author',
			'comment'     => 'Comment',
			'is_approved' => 'Is Approved',
			'created_on'  => 'Created On',
		];
	}

	/** @return \yii\db\ActiveQuery */
	public function getLang ()
	{
		return $this->hasOne(Lang::className(), [ 'id' => 'lang_id' ]);
	}

	/** @return \yii\db\ActiveQuery */
	public function getPost ()
	{
		return $this->hasOne(Post::className(), [ 'id' => 'post_id' ]);
	}

	/** @return \yii\db\ActiveQuery */
	public function getReplyTo ()
	{
		return $this->hasOne(PostComment::className(), [ "id" => "reply_comment_id" ]);
	}

	/** @return \yii\db\ActiveQuery */
	public function getReplies ()
	{
		return $this->hasMany(PostComment::className(), [ "reply_comment_id" => "id" ]);
	}

	/** @return \yii\db\ActiveQuery */
	public function getUser ()
	{
		return $this->hasOne(User::className(), [ "id" => "user_id" ]);
	}

	/**
	 * @inheritdoc
	 *
	 * @return PostCommentQuery the active query used by this AR class.
	 */
	public static function find ()
	{
		return new PostCommentQuery(get_called_class());
	}

	public function beforeSave ( $insert )
	{
		if (!parent::beforeSave($insert)) {
			return false;
		}

		//  on create, set the date that it was created on
		if ($insert) {
			$this->created_on = date(DateHelper::DATETIME_FORMAT);
		}

		//  when the is_approved flag is changed to "approved", then set the date that it was approved on.
		if ($this->isAttributeChanged("is_approved") && $this->is_approved === self::IS_APPROVED) {
			$this->approved_on = date(DateHelper::DATETIME_FORMAT);
		}
		
		return true;
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

	/**
	 * Verify if the post comment ID passed exists.
	 *
	 * @param int $commentId
	 *
	 * @return boolean
	 */
	public static function idExists ( $commentId )
	{
		return self::find()->byId($commentId)->exists();
	}
}
