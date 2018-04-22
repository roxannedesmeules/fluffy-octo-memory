<?php

namespace app\modules\v1\admin\models\tag;

use app\models\tag\AssoTagPost;

/**
 * Class AssoTagPostEx
 *
 * @package app\modules\v1\admin\models\tag
 */
class AssoTagPostEx extends AssoTagPost
{
	/**
	 * @inheritdoc
	 *
	 * @SWG\Definition(
	 *       definition = "PostTagRelation",
	 *       required   = { "post_id", "tag_id" },
	 *
	 *     @SWG\Property( property = "post_id", type = "integer" ),
	 *     @SWG\Property( property = "tag_id",  type = "integer" ),
	 * )
	 */
	public function rules () { return parent::rules(); }
}
