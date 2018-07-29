<?php

namespace app\models\post;

use app\helpers\ArrayHelperEx;
use app\helpers\DateHelper;
use app\models\app\File;
use app\models\app\Lang;
use app\models\user\User;
use Yii;

/**
 * This is the model class for table "post_lang".
 *
 * @property int           $post_id
 * @property int           $lang_id
 * @property int           $user_id
 * @property string        $title
 * @property string        $slug
 * @property string        $summary
 * @property string        $content
 * @property int           $file_id
 * @property string        $file_alt
 * @property string        $created_on
 * @property string        $updated_on
 *
 * Relations :
 * @property Lang          $lang
 * @property Post          $post
 * @property User          $user
 * @property File          $file
 * @property PostComment[] $comments
 */
abstract class PostLangBase extends \yii\db\ActiveRecord
{
    const SCENARIO_PUBLISHED = "published";

    const ERROR   = 0;
    const SUCCESS = 1;

    const ERR_ON_SAVE            = "ERR_ON_SAVE";
    const ERR_ON_DELETE          = "ERR_ON_DELETE";
    const ERR_NOT_FOUND          = "ERR_NOT_FOUND";
    const ERR_POST_NOT_FOUND     = "ERR_POST_NOT_FOUND";
    const ERR_LANG_NOT_FOUND     = "ERR_LANG_NOT_FOUND";
    const ERR_TRANSLATION_EXISTS = "ERR_TRANSLATION_ALREADY_EXISTS";
    const ERR_POST_PUBLISHED     = "ERR_POST_PUBLISHED";

    const ERR_ON_COVER_SAVE   = "ERR_ON_COVER_SAVE";
    const ERR_ON_COVER_UPDATE = "ERR_ON_COVER_UPDATE";
    const ERR_ON_COVER_DELETE = "ERR_ON_COVER_DELETE";

    const ERR_FIELD_REQUIRED   = "ERR_FIELD_VALUE_REQUIRED";
    const ERR_FIELD_TYPE       = "ERR_FIELD_VALUE_WRONG_TYPE";
    const ERR_FIELD_TOO_LONG   = "ERR_FIELD_VALUE_TOO_LONG";
    const ERR_FIELD_NOT_FOUND  = "ERR_FIELD_VALUE_NOT_FOUND";
    const ERR_FIELD_NOT_UNIQUE = "ERR_FIELD_VALUE_NOT_UNIQUE";

    /** @var array      list of extensions allowed */
    public static $extensions = ["jpg", "jpeg", "gif", "png",];

    /** @var int        size limit for each file upload */
    public static $maxsize = 10485760;

    public static $searchFields = ["title", "slug", "summary", "content"];

    /** @inheritdoc */
    public static function tableName() { return 'post_lang'; }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ["post_id", "required", "message" => self::ERR_FIELD_REQUIRED],
            ["post_id", "integer", "message" => self::ERR_FIELD_TYPE],
            [
                ['post_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Post::className(),
                'targetAttribute' => ['post_id' => 'id'],
                "message"         => self::ERR_FIELD_NOT_FOUND,
            ],

            ["lang_id", "required", "message" => self::ERR_FIELD_REQUIRED],
            ["lang_id", "integer", "message" => self::ERR_FIELD_TYPE],
            [
                ['lang_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Lang::className(),
                'targetAttribute' => ['lang_id' => 'id'],
                "message"         => self::ERR_FIELD_NOT_FOUND,
            ],

            [
                ['post_id', 'lang_id'],
                'unique',
                'targetAttribute' => ['post_id', 'lang_id'],
                "message"         => self::ERR_FIELD_NOT_UNIQUE,
            ],

            ["user_id", "required", "message" => self::ERR_FIELD_REQUIRED],
            ["user_id", "integer", "message" => self::ERR_FIELD_TYPE],
            [
                ['user_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::className(),
                'targetAttribute' => ['user_id' => 'id'],
                "message"         => self::ERR_FIELD_NOT_FOUND,
            ],

            ["title", "required", "message" => self::ERR_FIELD_REQUIRED],
            ["title", "string", "max" => 255, "tooLong" => self::ERR_FIELD_TOO_LONG,],

            ["slug", "required", "on" => self::SCENARIO_PUBLISHED, "message" => self::ERR_FIELD_REQUIRED],
            ["slug", "string", "max" => 255, "tooLong" => self::ERR_FIELD_TOO_LONG,],
            ["slug", "unique", "targetAttribute" => ["slug", "lang_id"], "message" => self::ERR_FIELD_NOT_UNIQUE],

            ["summary", "required", "on" => self::SCENARIO_PUBLISHED, "message" => self::ERR_FIELD_REQUIRED],
            ["summary", "string", "max" => 180, "tooLong" => self::ERR_FIELD_TOO_LONG],

            ["content", "required", "on" => self::SCENARIO_PUBLISHED, "message" => self::ERR_FIELD_REQUIRED],
            ["content", "string", "message" => self::ERR_FIELD_TYPE],

            ["file_id", "required", "on" => self::SCENARIO_PUBLISHED, "message" => self::ERR_FIELD_REQUIRED],
            ["file_id", "integer", "message" => self::ERR_FIELD_TYPE],
            [
                ["file_id"],
                "exist",
                "skipOnError"     => true,
                "targetClass"     => File::className(),
                "targetAttribute" => ["file_id" => "id"],
                "message"         => self::ERR_FIELD_NOT_FOUND,
            ],

            ["file_alt", "required", "on" => self::SCENARIO_PUBLISHED, "message" => self::ERR_FIELD_REQUIRED],
            ["file_alt", "string", "message" => self::ERR_FIELD_TYPE],

            ["created_on", "safe"],
            ["updated_on", "safe"],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'post_id'    => Yii::t('app.post', 'Post ID'),
            'lang_id'    => Yii::t('app.post', 'Lang ID'),
            'user_id'    => Yii::t('app.post', 'User ID'),
            'title'      => Yii::t('app.post', 'Title'),
            'slug'       => Yii::t('app.post', 'Slug'),
            'content'    => Yii::t('app.post', 'Content'),
            'created_on' => Yii::t('app.post', 'Created On'),
            'updated_on' => Yii::t('app.post', 'Updated On'),
        ];
    }

    /** @return \yii\db\ActiveQuery */
    public function getComments()
    {
        return $this->hasMany(PostComment::class, ["lang_id" => "lang_id", "post_id" => "post_id"]);
    }

    /** @return \yii\db\ActiveQuery */
    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    /** @return \yii\db\ActiveQuery */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /** @return \yii\db\ActiveQuery */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /** @return \yii\db\ActiveQuery */
    public function getFile()
    {
        return $this->hasOne(File::className(), ["id" => "file_id"]);
    }

    /**
     * @inheritdoc
     * @return PostLangQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostLangQuery(get_called_class());
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->user_id = Yii::$app->getUser()->getId();

            if (YII_ENV === 'test') {
                $this->user_id = 1;
            }
        }

        if (!parent::beforeValidate()) {
            return false;
        }

        return true;
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        switch ($insert) {
            case true:
                $this->created_on = date(DateHelper::DATETIME_FORMAT);
                break;

            case false:
                $this->updated_on = date(DateHelper::DATETIME_FORMAT);
                break;
        }

        return true;
    }

    /** @inheritdoc */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        //  find post and mark it as updated
        $post = Post::find()->id($this->post_id)->one();

        $post->markUpdated();
    }

    /**
     * Build an array to use when returning from another method. The status will automatically
     * set to ERROR, then $error passed in param will be associated to the error key.
     *
     * @param $error
     *
     * @return array
     */
    public static function buildError($error)
    {
        return ["status" => self::ERROR, "error" => $error];
    }

    /**
     * Build an array to use when returning from another method. The status will be automatically
     * set to SUCCESS, then the $params will be merged with the array and be returned.
     *
     * @param array $params
     *
     * @return array
     */
    public static function buildSuccess($params)
    {
        return ArrayHelperEx::merge(["status" => self::SUCCESS], $params);
    }

    public static function translationExists($postId, $langId)
    {
        return self::find()->byPost($postId)->byLang($langId)->exists();
    }
}
