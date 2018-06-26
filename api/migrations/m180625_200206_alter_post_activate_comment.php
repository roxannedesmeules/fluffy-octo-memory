<?php

use yii\db\Migration;

/**
 * Class m180625_200206_alter_post_activate_comment
 */
class m180625_200206_alter_post_activate_comment extends Migration
{
	const TABLE  = "post";
	const COLUMN = "is_comment_enabled";

	/** @inheritdoc */
	public function safeUp ()
	{
		$this->addColumn(
			self::TABLE, self::COLUMN,
			$this->smallInteger(1)->defaultValue(1)->after("is_featured")->comment("Flag indicating if comments can be created for this post"));
	}

	/** @inheritdoc */
	public function safeDown ()
	{
		$this->dropColumn(self::TABLE, self::COLUMN);
	}
}
