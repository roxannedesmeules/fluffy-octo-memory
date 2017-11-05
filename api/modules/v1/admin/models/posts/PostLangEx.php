<?php
namespace app\modules\v1\admin\models\posts;

use app\models\app\Lang;
use app\models\post\PostLang;

/**
 * Class PostLangEx
 *
 * @package app\modules\v1\admin\models\posts
 */
class PostLangEx extends PostLang
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostTranslation",
	 *
	 *     @SWG\Property( property = "language", type = "string" ),
	 *     @SWG\Property( property = "title",    type = "string" ),
	 *     @SWG\Property( property = "slug",     type = "string" ),
	 *     @SWG\Property( property = "content",  type = "string" ),
	 * )
	 */
	public function fields ()
	{
		return [
			"language" => function ( self $model ) { return $model->lang->icu; },
			"title",
			"slug",
			"content",
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostTranslationForm",
	 *       required   = { "lang_id", "title", "slug", "content" },
	 *
	 *     @SWG\Property( property = "lang_id", type = "integer" ),
	 *     @SWG\Property( property = "title", type = "string" ),
	 *     @SWG\Property( property = "slug", type = "string" ),
	 *     @SWG\Property( property = "content", type = "string" ),
	 * )
	 */
	public function rules ()
	{
		return [
			[ "lang_id", "required" ],
			[ "lang_id", "exist", "targetClass" => Lang::className(), "targetAttribute" => [ "lang_id" => "id" ] ],

			[ "title", "required" ],
			[ "title", "string", "max" => 255 ],

			[ "slug", "required" ],
			[ "slug", "string", "max" => 255 ],
			[ "slug", "unique" ],

			[ "content", "string" ],
		];
	}
}
